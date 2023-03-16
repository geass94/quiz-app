const form = document.querySelector('#quiz-form');
const addQuestionBtn = document.querySelector('#add-question-btn');
const questionsContainer = document.querySelector('#questions-container');
let questionCount = 0;

addQuestionBtn.addEventListener('click', () => {
    questionCount++;

    const questionDiv = document.createElement('div');
    questionDiv.classList.add('question');
    const questionInputs = document.createElement('div');
    questionInputs.classList.add('wrapper');
    const questionLabel = document.createElement('label');
    questionLabel.textContent = `Question ${questionCount}:`;
    const questionInput = document.createElement('textarea');
    // questionInput.type = 'text';
    questionInput.name = `question${questionCount}`;

    const answersDiv = document.createElement('div');
    answersDiv.classList.add('answers');

    const addAnswerBtn = document.createElement('button');
    addAnswerBtn.type = 'button';
    addAnswerBtn.textContent = 'Add Answer';
    addAnswerBtn.addEventListener('click', () => {
        const answerInput = document.createElement('textarea');
        // answerInput.type = 'text';
        answerInput.name = `answer${questionCount}[]`;

        const isCorrectLabel = document.createElement('label');
        isCorrectLabel.textContent = `Is Correct?:`;
        const isCorrectInput = document.createElement('input');
        isCorrectInput.type = 'radio';
        isCorrectInput.name = `isCorrect${questionCount}[]`;

        const answerDiv = document.createElement('div');
        answerDiv.classList.add('answer');
        answerDiv.appendChild(answerInput);
        answerDiv.appendChild(isCorrectLabel);
        answerDiv.appendChild(isCorrectInput);

        answersDiv.appendChild(answerDiv);
    });
    questionInputs.appendChild(questionLabel);
    questionInputs.appendChild(questionInput);
    questionDiv.appendChild(questionInputs);
    questionDiv.appendChild(answersDiv);
    questionDiv.appendChild(addAnswerBtn);

    questionsContainer.appendChild(questionDiv);
});

form.addEventListener('submit', (event) => {
    event.preventDefault();

    const quizName = form.elements.quizName.value;
    const quizType = form.elements.quizType.value;
    const duration = form.elements.duration.value;
    const questions = [];

    const questionDivs = document.querySelectorAll('.question');
    questionDivs.forEach((questionDiv) => {
        const questionText = questionDiv.querySelector('textarea').value;

        const answerDivs = questionDiv.querySelectorAll('.answer');
        const answers = [];
        answerDivs.forEach((answerDiv) => {
            const answerText = answerDiv.querySelector('textarea').value;
            const isCorrect = answerDiv.querySelector('input[type="radio"]').checked;

            const answer = {
                answer: answerText,
                isCorrect: isCorrect
            };

            answers.push(answer);
        });

        const question = {
            question: questionText,
            answers: answers
        };

        questions.push(question);
    });

    const quiz = {
        quizName: quizName,
        quizType: quizType,
        questions: questions,
        duration: duration
    };

    axios.post('/ajax/quiz', quiz).then(res => {
        window.local.reload();
    }).catch(e => {
        console.log(e)
    })
});
