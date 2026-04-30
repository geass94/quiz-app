<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Quiz History') }}
        </h2>
    </x-slot>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/quiz.css') }}">
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="history-page">
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Score</th>
                                <th>Unanswered</th>
                                <th>Submitted</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sessions as $session)
                                <tr>
                                    <td>{{ $session->user?->name ?? '—' }}</td>
                                    <td>{{ $session->user?->last_name ?? '—' }}</td>
                                    <td>{{ $session->user?->email ?? '—' }}</td>
                                    <td>{{ $session->score }} / {{ $session->total_questions }}</td>
                                    <td>{{ $session->unanswered_count }}</td>
                                    <td>{{ $session->submitted_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="empty">No completed sessions yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if ($sessions->hasPages())
                        <div class="history-pagination">{{ $sessions->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
