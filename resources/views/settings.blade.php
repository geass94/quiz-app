<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if (session('status') === 'mode-updated')
                    <p class="text-sm text-green-600 mb-4">
                        Mode updated. Any in-progress quiz session was reset.
                    </p>
                @endif

                <form method="POST" action="{{ route('settings.update') }}">
                    @csrf
                    @method('PATCH')

                    <fieldset>
                        <legend class="font-semibold mb-2">Quiz mode</legend>

                        <label class="block mb-1">
                            <input type="radio" name="mode" value="{{ $modes['BINARY'] }}"
                                {{ $mode === $modes['BINARY'] ? 'checked' : '' }}>
                            Binary (Yes/No) — default
                        </label>

                        <label class="block">
                            <input type="radio" name="mode" value="{{ $modes['MULTI'] }}"
                                {{ $mode === $modes['MULTI'] ? 'checked' : '' }}>
                            Multiple choice (3 answers)
                        </label>
                    </fieldset>

                    @error('mode')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror

                    <p class="text-xs text-gray-500 mt-3">
                        Changing the mode resets any in-progress quiz session.
                    </p>

                    <button type="submit"
                        class="mt-4 px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700">
                        Save
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
