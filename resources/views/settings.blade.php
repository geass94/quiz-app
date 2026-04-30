<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/quiz.css') }}">
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="settings-card">
                    @if (session('status') === 'mode-updated')
                        <p class="status">Mode updated. Any in-progress quiz session was reset.</p>
                    @endif

                    <form method="POST" action="{{ route('settings.update') }}">
                        @csrf
                        @method('PATCH')

                        <fieldset>
                            <legend>Quiz mode</legend>

                            <label class="option">
                                <input type="radio" name="mode" value="{{ $modes['BINARY'] }}"
                                    {{ $mode === $modes['BINARY'] ? 'checked' : '' }}>
                                Binary (Yes/No) — default
                            </label>

                            <label class="option">
                                <input type="radio" name="mode" value="{{ $modes['MULTI'] }}"
                                    {{ $mode === $modes['MULTI'] ? 'checked' : '' }}>
                                Multiple choice (3 answers)
                            </label>
                        </fieldset>

                        @error('mode')
                            <p class="error">{{ $message }}</p>
                        @enderror

                        <p class="hint">Changing the mode resets any in-progress quiz session.</p>

                        <button type="submit" class="session-button">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
