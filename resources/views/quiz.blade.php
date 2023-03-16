<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div id="overlay">
                    <div id="modal">
                        <h2>Welcome to My Quiz!</h2>
                        <p>You have <span id="time-remaining"></span> minutes to complete the quiz.</p>
                        <button id="start-button">Start</button>
                        <button id="cancel-button">Cancel</button>
                    </div>
                </div>
                <div id="quiz-completed-modal" class="modal">
                    <div class="modal-content">
                        <h2>Quiz Completed</h2>
                        <div class="leaderboard">
                            <table id="table">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Score</th>
                                    <th>Time Spent</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>

                            <div class="buttons">
                                <button id="restart-btn">Restart</button>
                                <button id="main-page-btn">Go to Main Page</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="quiz-container">
                    <h1>My Quiz</h1>
                    <div id="timer"></div>
                    <div id="quiz-form">
                        <div id="questions-container"></div>
                        <button id="submit-button">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const quiz = {!! $quiz !!};
        let userQuizId = null;

        // Convert time to minutes
        let timeInMinutes = Math.floor(parseInt(quiz.time) / 60);
        let timeInSeconds = parseInt(quiz.time) % 60;

        // Overlay elements
        const overlay = document.getElementById("overlay");
        const modal = document.getElementById("modal");
        const startButton = document.getElementById("start-button");
        const cancelButton = document.getElementById("cancel-button");

        // Quiz elements
        const timer = document.getElementById("timer");
        const questionsContainer = document.getElementById("questions-container");
        const submitButton = document.getElementById("submit-button");

        // Quiz variables
        let quizStarted = false;
        let timeLeft = parseInt(quiz.time);
        let intervalId;
        let answeredQuestions = [];

        // Functions
        function startQuiz() {
            axios.post('/ajax/quiz/start', {
                'quizId': quiz.id
            }).then(res => {
                userQuizId = res.data.data.id
                // Hide modal
                overlay.style.display = "none";

                // Display timer
                displayTimer();

                // Display questions
                displayQuestions();

                // Start timer
                intervalId = setInterval(updateTimer, 1000);
            }).catch(err => {

            })
        }

        function displayTimer() {
            timer.innerHTML = `${timeInMinutes.toString().padStart(2, "0")}:${timeInSeconds.toString().padStart(2, "0")}`;
        }

        function updateTimer() {
            timeLeft--;
            timeInMinutes = Math.floor(timeLeft / 60);
            timeInSeconds = timeLeft % 60;
            displayTimer();
            if (timeLeft === 0) {
                clearInterval(intervalId);
                disableUnansweredQuestions();
                submitQuiz();
            }
        }

        function displayQuestions() {
            questionsContainer.innerHTML = "";
            quiz.questions.forEach((question) => {
                const questionContainer = document.createElement("div");
                questionContainer.classList.add("question-container");

                const questionText = document.createElement("p");
                questionText.classList.add("question");
                questionText.innerHTML = question.question;
                questionContainer.appendChild(questionText);

                const answerContainer = document.createElement("div");
                question.answers.forEach((answer) => {
                    const answerButton = document.createElement("button");
                    answerButton.classList.add("answer-button");
                    answerButton.innerHTML = answer.answer;
                    answerButton.dataset.questionId = question.id;
                    answerButton.dataset.answerId = answer.id;
                    answerButton.addEventListener("click", handleAnswerClick);
                    answerContainer.appendChild(answerButton);
                });
                questionContainer.appendChild(answerContainer);

                questionsContainer.appendChild(questionContainer);
            });
        }

        function handleAnswerClick(event) {
            const questionId = parseInt(event.target.dataset.questionId);
            const answerId = parseInt(event.target.dataset.answerId);
            const questionIndex = quiz.questions.findIndex((question) => question.id === questionId);

            // Mark answer as selected
            quiz.questions[questionIndex].answers.forEach((answer) => {
                answer.selected = false;
                if (answer.id === answerId) {
                    answer.selected = true;
                }
            });

            // Disable other answers
            const answerButtons = event.target.parentNode.querySelectorAll(".answer-button");
            answerButtons.forEach((button) => {
                if (button.dataset.questionId === event.target.dataset.questionId) {
                    if (button.dataset.answerId !== event.target.dataset.answerId) {
                        button.disabled = true;
                        button.classList.add("disabled");
                    }
                }
            });

            // Add to answered questions
            answeredQuestions = answeredQuestions.filter((answeredQuestion) => answeredQuestion.questionId !== questionId);
            answeredQuestions.push({ questionId, answerId });
        }

        function disableUnansweredQuestions() {
            quiz.questions.forEach((question) => {
                const questionIndex = quiz.questions.findIndex((q) => q.id === question.id);
                const answeredQuestionIndex = answeredQuestions.findIndex((answeredQuestion) => answeredQuestion.questionId === question.id);
                if (answeredQuestionIndex === -1) {
                    const answerButtons = questionsContainer.querySelectorAll(`[data-question-id="${question.id}"]`);
                    answerButtons.forEach((button) => {
                        button.disabled = true;
                        button.classList.add("disabled");
                    });
                    quiz.questions[questionIndex].answers.forEach((answer) => {
                        answer.selected = false;
                    });
                }
            });
        }

        function submitQuiz() {
            const timeSpent = parseInt(quiz.time) - timeLeft;
            const completedQuiz = {
                name: quiz.name,
                type: quiz.type,
                timeSpent: timeSpent,
                answeredQuestions: answeredQuestions,
                userQuizId: userQuizId
            };
            clearInterval(intervalId);
            console.log(completedQuiz);
            axios.post('/ajax/quiz/submit', completedQuiz).then(res => {
                console.log(res.data)
                const modal = document.getElementById("quiz-completed-modal");
                // const modalMessage = document.createElement("p");
                // modalMessage.textContent = "Thank you for completing the quiz!";
                // modal.querySelector(".modal-content").appendChild(modalMessage);
                modal.style.display = "block";
                loadLeaderboards(res.data.data);
            }).catch(e => {

            })
        }

        // Event listeners
        startButton.addEventListener("click", startQuiz);

        cancelButton.addEventListener("click", () => {
            window.location.href = "/dashboard";
        });

        submitButton.addEventListener("click", submitQuiz);
        // Display modal
        modal.querySelector("p").innerHTML = `The quiz has a maximum time of ${timeInMinutes}:${timeInSeconds.toString().padStart(2, "0")} minutes.`;
        overlay.style.display = "block";


    //     Leaderboards

        function loadLeaderboards(leaderboard) {
            const table = document.getElementById("table");
            const tbody = table.getElementsByTagName("tbody")[0];

            // Function to convert seconds to minutes format
            const secondsToMinutes = (seconds) => {
                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = seconds % 60;
                return `${minutes.toString().padStart(2, "0")}:${remainingSeconds.toString().padStart(2, "0")}`;
            }

            // Create table rows and columns using data array
            leaderboard.forEach((item) => {
                const row = document.createElement("tr");
                const name = document.createElement("td");
                const email = document.createElement("td");
                const score = document.createElement("td");
                const timeSpent = document.createElement("td");

                name.innerText = item.name;
                email.innerText = item.email;
                score.innerText = item.score;
                timeSpent.innerText = secondsToMinutes(item.timeSpent);

                row.appendChild(name);
                row.appendChild(email);
                row.appendChild(score);
                row.appendChild(timeSpent);

                tbody.appendChild(row);
            });

            // Event listener for restart button
            const restartBtn = document.getElementById("restart-btn");
            restartBtn.addEventListener("click", () => {
                console.log("Okay I am restarting");
                window.location.reload();
            });

            // Event listener for go to main page button
            const mainPageBtn = document.getElementById("main-page-btn");
            mainPageBtn.addEventListener("click", () => {
                window.location.href = "/dashboard";
            });
        }
    </script>
</x-app-layout>
