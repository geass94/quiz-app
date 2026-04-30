<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/quiz.css') }}">
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div id="start-card" class="session-card">
                    <h2>Famous Quote Quiz</h2>
                    <p>Mode: <strong>{{ $mode }}</strong></p>
                    <p>You have <span id="time-display"></span> to complete the quiz.</p>
                    <p>
                        <button id="start-button" class="session-button">Start</button>
                    </p>
                </div>

                <div id="quiz-container" class="quiz-play is-hidden">
                    <div class="timer" id="timer"></div>
                    <div id="questions-container"></div>
                    <button id="submit-button" class="session-button">Submit</button>
                </div>

                <div id="results-card" class="session-card is-hidden">
                    <h2>Session complete</h2>
                    <p>Score: <strong><span id="result-score"></span> / <span id="result-total"></span></strong></p>
                    <p>Unanswered: <span id="result-unanswered"></span></p>
                    <p>Time spent: <span id="result-time"></span></p>
                    <p>
                        <button id="restart-button" class="session-button">Restart</button>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const SESSION_DURATION = {{ (int) $duration }};

        let sessionId = null;
        let timeLeft = SESSION_DURATION;
        let intervalId = null;
        let questions = [];
        let answeredQuestions = [];

        const startCard = document.getElementById('start-card');
        const quizContainer = document.getElementById('quiz-container');
        const resultsCard = document.getElementById('results-card');

        const startButton = document.getElementById('start-button');
        const submitButton = document.getElementById('submit-button');
        const restartButton = document.getElementById('restart-button');

        const timerEl = document.getElementById('timer');
        const questionsContainer = document.getElementById('questions-container');
        const timeDisplay = document.getElementById('time-display');

        function fmtTime(seconds) {
            const m = Math.floor(seconds / 60).toString().padStart(2, '0');
            const s = (seconds % 60).toString().padStart(2, '0');
            return `${m}:${s}`;
        }

        function show(el) { el.classList.remove('is-hidden'); }
        function hide(el) { el.classList.add('is-hidden'); }

        timeDisplay.textContent = fmtTime(SESSION_DURATION);

        startButton.addEventListener('click', startSession);
        submitButton.addEventListener('click', submitSession);
        restartButton.addEventListener('click', () => window.location.reload());

        async function startSession() {
            startButton.disabled = true;
            try {
                const res = await axios.post('/ajax/session/start');
                const data = res.data.data;
                sessionId = data.sessionId;
                timeLeft = data.duration;
                questions = data.questions;

                hide(startCard);
                show(quizContainer);
                renderQuestions();
                renderTimer();
                intervalId = setInterval(tickTimer, 1000);
            } catch (e) {
                console.log(e);
                startButton.disabled = false;
            }
        }

        function renderQuestions() {
            questionsContainer.innerHTML = '';
            questions.forEach((q) => {
                const qDiv = document.createElement('div');
                qDiv.className = 'question-container';

                const qText = document.createElement('p');
                qText.className = 'question';
                qText.textContent = q.question;
                qDiv.appendChild(qText);

                const aWrap = document.createElement('div');
                q.answers.forEach((a) => {
                    const btn = document.createElement('button');
                    btn.className = 'answer-button';
                    btn.textContent = a.answer;
                    btn.dataset.questionId = q.id;
                    btn.dataset.answerId = a.id;
                    btn.addEventListener('click', onAnswerClick);
                    aWrap.appendChild(btn);
                });
                qDiv.appendChild(aWrap);

                questionsContainer.appendChild(qDiv);
            });
        }

        function renderTimer() {
            timerEl.textContent = fmtTime(Math.max(timeLeft, 0));
        }

        function tickTimer() {
            timeLeft--;
            renderTimer();
            if (timeLeft <= 0) {
                clearInterval(intervalId);
                submitSession();
            }
        }

        function onAnswerClick(e) {
            const qid = e.target.dataset.questionId;
            const aid = parseInt(e.target.dataset.answerId, 10);

            const buttons = e.target.parentNode.querySelectorAll('.answer-button');
            buttons.forEach((b) => {
                b.disabled = true;
                b.classList.remove('selected');
            });
            e.target.classList.add('selected');

            answeredQuestions = answeredQuestions.filter((x) => String(x.questionId) !== String(qid));
            answeredQuestions.push({ questionId: parseInt(qid, 10), answerId: aid });
        }

        async function submitSession() {
            if (sessionId === null) return;
            clearInterval(intervalId);
            submitButton.disabled = true;
            const timeSpent = SESSION_DURATION - Math.max(timeLeft, 0);
            try {
                const res = await axios.post('/ajax/session/submit', {
                    sessionId, timeSpent, answeredQuestions,
                });
                const data = res.data.data;
                hide(quizContainer);
                document.getElementById('result-score').textContent = data.score;
                document.getElementById('result-total').textContent = data.totalQuestions;
                document.getElementById('result-unanswered').textContent = data.unanswered;
                document.getElementById('result-time').textContent = fmtTime(data.timeSpent);
                show(resultsCard);
                sessionId = null;
            } catch (e) {
                console.log(e);
                submitButton.disabled = false;
            }
        }
    </script>
</x-app-layout>
