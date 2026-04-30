const form = document.querySelector('#quiz-form');
const addQuestionBtn = document.querySelector('#add-question-btn');
const questionsContainer = document.querySelector('#questions-container');
const quizTypeSelect = document.querySelector('#quiz-type');

let questionCount = 0;

quizTypeSelect.dataset.lastValue = quizTypeSelect.value;

quizTypeSelect.addEventListener('change', () => {
    const existing = questionsContainer.querySelectorAll('.question');
    if (existing.length > 0) {
        const proceed = confirm('Changing the quiz type will clear all questions you have added. Continue?');
        if (!proceed) {
            quizTypeSelect.value = quizTypeSelect.dataset.lastValue;
            return;
        }
        existing.forEach((q) => q.remove());
        questionCount = 0;
    }
    quizTypeSelect.dataset.lastValue = quizTypeSelect.value;
});

addQuestionBtn.addEventListener('click', () => {
    questionCount++;
    const type = quizTypeSelect.value;

    const questionDiv = document.createElement('div');
    questionDiv.classList.add('question');

    const wrapper = document.createElement('div');
    wrapper.classList.add('wrapper');
    const label = document.createElement('label');
    label.textContent = `Question ${questionCount}`;
    const textarea = document.createElement('textarea');
    textarea.name = `question${questionCount}`;
    wrapper.appendChild(label);
    wrapper.appendChild(textarea);
    questionDiv.appendChild(wrapper);

    if (type === 'BINARY') {
        questionDiv.appendChild(buildBinaryAnswers(questionCount));
    } else {
        questionDiv.appendChild(buildMultiAnswers(questionCount));
    }

    questionsContainer.appendChild(questionDiv);
});

function buildBinaryAnswers(idx) {
    const wrap = document.createElement('div');
    wrap.classList.add('answers');

    const heading = document.createElement('label');
    heading.textContent = 'Correct answer:';
    wrap.appendChild(heading);

    ['Yes', 'No'].forEach((opt, i) => {
        const row = document.createElement('label');
        row.classList.add('answer');

        const input = document.createElement('input');
        input.type = 'radio';
        input.name = `correct${idx}`;
        input.value = opt;
        if (i === 0) input.checked = true;

        row.appendChild(input);
        row.appendChild(document.createTextNode(' ' + opt));
        wrap.appendChild(row);
    });

    return wrap;
}

function buildMultiAnswers(idx) {
    const wrap = document.createElement('div');
    wrap.classList.add('answers');

    const heading = document.createElement('label');
    heading.textContent = 'Answers (fill 2 or 3, mark exactly one as correct):';
    wrap.appendChild(heading);

    for (let i = 0; i < 3; i++) {
        const row = document.createElement('div');
        row.classList.add('answer');

        const ta = document.createElement('textarea');
        ta.name = `answer${idx}_${i}`;
        ta.rows = 1;
        ta.placeholder = `Answer ${i + 1}`;

        const radio = document.createElement('input');
        radio.type = 'radio';
        radio.name = `correct${idx}`;
        radio.value = String(i);
        if (i === 0) radio.checked = true;

        const radioLabel = document.createElement('label');
        radioLabel.textContent = 'Correct';

        row.appendChild(ta);
        row.appendChild(radio);
        row.appendChild(radioLabel);
        wrap.appendChild(row);
    }

    return wrap;
}

form.addEventListener('submit', (event) => {
    event.preventDefault();

    const quizName = form.elements.quizName.value;
    const quizType = form.elements.quizType.value;
    const duration = form.elements.duration.value;
    const questions = [];

    questionsContainer.querySelectorAll('.question').forEach((qDiv) => {
        const questionText = qDiv.querySelector('.wrapper textarea').value;
        let answers;

        if (quizType === 'BINARY') {
            const checked = qDiv.querySelector('.answers input[type="radio"]:checked');
            const correct = checked ? checked.value : 'Yes';
            answers = [
                { answer: 'Yes', isCorrect: correct === 'Yes' },
                { answer: 'No', isCorrect: correct === 'No' },
            ];
        } else {
            answers = [];
            const checked = qDiv.querySelector('.answers input[type="radio"]:checked');
            const correctIdx = checked ? parseInt(checked.value, 10) : -1;
            qDiv.querySelectorAll('.answer textarea').forEach((ta, i) => {
                const text = ta.value.trim();
                if (text.length > 0) {
                    answers.push({
                        answer: text,
                        isCorrect: i === correctIdx,
                    });
                }
            });
        }

        questions.push({ question: questionText, answers });
    });

    const payload = { quizName, quizType, questions, duration };

    axios.post('/ajax/quiz', payload).then((res) => {
        if (res.data && res.data.error) {
            alert(res.data.message || 'Could not save quiz.');
            return;
        }
        window.location.reload();
    }).catch((e) => {
        const msg = e?.response?.data?.message || 'Could not save quiz.';
        alert(msg);
    });
});
