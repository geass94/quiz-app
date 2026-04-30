<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Quiz') }}
        </h2>
    </x-slot>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/quiz.css') }}">
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form id="quiz-form" class="quiz-form">
                    <label for="quiz-name">Quiz Name</label>
                    <input type="text" id="quiz-name" name="quizName">

                    <label for="quiz-duration">Quiz Duration in Seconds</label>
                    <input type="number" id="quiz-duration" name="duration">

                    <label for="quiz-type">Quiz Type</label>
                    <select name="quizType" id="quiz-type">
                        <option value="BINARY">Yes / No</option>
                        <option value="MULTI">Multi Choice</option>
                    </select>

                    <div id="questions-container">
                        <h3>Questions</h3>
                        <button type="button" id="add-question-btn">Add Question</button>
                    </div>

                    <button type="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/quiz-form.js') }}"></script>
</x-app-layout>
