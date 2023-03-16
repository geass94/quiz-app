<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <style>
        #quiz-form {
            display: flex;
            flex-direction: column;
            max-width: 500px;
            margin: auto;
        }

        #quiz-form label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        #quiz-form input[type="text"],
        #quiz-form textarea,
        #quiz-form input[type="number"],
        #quiz-form select {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: none;
            background-color: #f2f2f2;
            font-size: 16px;
            color: #333;
        }

        #quiz-form select {
            appearance: none;
            -webkit-appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg fill="#666" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/></svg>');
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 20px;
        }

        #questions-container {
            margin-top: 30px;
        }

        #questions-container h2 {
            margin-bottom: 10px;
        }

        .question {
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .wrapper {
            margin-bottom: 15px;
        }

        .wrapper label {
            display: block;
        }

        .answers {
            display: flex;
            flex-direction: column;
        }

        .answer {
            display: flex;
            margin-bottom: 10px;
        }

        textarea,
        input[type="number"],
        input[type="text"] {
            flex: 1;
            margin-right: 10px;
            width: 100%;
        }

        .answer label {
            margin-right: 10px;
        }

        .answer input[type="radio"] {
            margin-top: 5px;
        }

        button[type="button"],
        button[type="submit"] {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }

        button[type="button"]:hover,
        button[type="submit"]:hover {
            background-color: #555;
        }
    </style>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <h1>Create Quiz</h1>
                <form id="quiz-form">
                    <label for="quiz-name">Quiz Name:</label>
                    <input type="text" id="quiz-name" name="quizName"><br>

                    <label for="quiz-duration">Quiz Duration in Seconds:</label>
                    <input type="number" id="quiz-duration" name="duration"><br>

                    <label for="quiz-type">Quiz Type:</label>
                    <select name="quizType" id="quiz-type">
                        <option value="BINARY">Yes / No</option>
                        <option value="MULTI">Multi Choice</option>
                    </select><br>

                    <div id="questions-container">
                        <h2>Questions</h2>
                        <button type="button" id="add-question-btn">Add Question</button>
                        <br/><br/>
                    </div>

                    <button type="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <script src="{{asset('js/quiz-form.js')}}"></script>
</x-app-layout>
