<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="quiz-container">
                    <ul class="quiz-list">
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script>
        const quizzes = {!! $quizzes !!};

        function generateQuizList(quizzes) {
            const quizList = document.querySelector('.quiz-list');
            quizzes.forEach((quiz) => {
                const quizItem = document.createElement('li');
                quizItem.innerHTML = `
    <div class="quiz-name">${quiz.name}</div>
      <div class="quiz-status ${quiz.completed ? 'quiz-status--completed' : 'quiz-status--incomplete'}"></div>
      <a href="/quiz/${quiz.id}" class="quiz-link">Take a Quiz</a>
    `;
                quizList.appendChild(quizItem);
            });
        }

        generateQuizList(quizzes);
    </script>
</x-app-layout>
