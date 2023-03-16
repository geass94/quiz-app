/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***********************************!*\
  !*** ./resources/js/quiz-form.js ***!
  \***********************************/
var form = document.querySelector('#quiz-form');
var addQuestionBtn = document.querySelector('#add-question-btn');
var questionsContainer = document.querySelector('#questions-container');
var questionCount = 0;
addQuestionBtn.addEventListener('click', function () {
  questionCount++;
  var questionDiv = document.createElement('div');
  questionDiv.classList.add('question');
  var questionInputs = document.createElement('div');
  questionInputs.classList.add('wrapper');
  var questionLabel = document.createElement('label');
  questionLabel.textContent = "Question ".concat(questionCount, ":");
  var questionInput = document.createElement('textarea');
  // questionInput.type = 'text';
  questionInput.name = "question".concat(questionCount);
  var answersDiv = document.createElement('div');
  answersDiv.classList.add('answers');
  var addAnswerBtn = document.createElement('button');
  addAnswerBtn.type = 'button';
  addAnswerBtn.textContent = 'Add Answer';
  addAnswerBtn.addEventListener('click', function () {
    var answerInput = document.createElement('textarea');
    // answerInput.type = 'text';
    answerInput.name = "answer".concat(questionCount, "[]");
    var isCorrectLabel = document.createElement('label');
    isCorrectLabel.textContent = "Is Correct?:";
    var isCorrectInput = document.createElement('input');
    isCorrectInput.type = 'radio';
    isCorrectInput.name = "isCorrect".concat(questionCount, "[]");
    var answerDiv = document.createElement('div');
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
form.addEventListener('submit', function (event) {
  event.preventDefault();
  var quizName = form.elements.quizName.value;
  var quizType = form.elements.quizType.value;
  var duration = form.elements.duration.value;
  var questions = [];
  var questionDivs = document.querySelectorAll('.question');
  questionDivs.forEach(function (questionDiv) {
    var questionText = questionDiv.querySelector('textarea').value;
    var answerDivs = questionDiv.querySelectorAll('.answer');
    var answers = [];
    answerDivs.forEach(function (answerDiv) {
      var answerText = answerDiv.querySelector('textarea').value;
      var isCorrect = answerDiv.querySelector('input[type="radio"]').checked;
      var answer = {
        answer: answerText,
        isCorrect: isCorrect
      };
      answers.push(answer);
    });
    var question = {
      question: questionText,
      answers: answers
    };
    questions.push(question);
  });
  var quiz = {
    quizName: quizName,
    quizType: quizType,
    questions: questions,
    duration: duration
  };
  axios.post('/ajax/quiz', quiz).then(function (res) {
    window.local.reload();
  })["catch"](function (e) {
    console.log(e);
  });
});
/******/ })()
;