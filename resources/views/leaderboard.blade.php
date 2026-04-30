<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Top Scorers') }}
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
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Score</th>
                                <th>Time used</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rows as $row)
                                @php
                                    $timeSpent = max(\App\Models\Quiz\Quiz::SESSION_DURATION - (int) $row->time_left, 0);
                                    $h = (int) floor($timeSpent / 3600);
                                    $m = (int) floor(($timeSpent % 3600) / 60);
                                    $s = $timeSpent % 60;
                                    $rank = ($rows->firstItem() ?? 1) + $loop->index;
                                @endphp
                                <tr>
                                    <td>{{ $rank }}</td>
                                    <td>{{ $row->user?->name ?? '—' }}</td>
                                    <td>{{ $row->user?->email ?? '—' }}</td>
                                    <td>{{ $row->score }} / {{ $row->total_questions }}</td>
                                    <td>{{ sprintf('%02d:%02d:%02d', $h, $m, $s) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="empty">No scores yet — be the first.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if ($rows->hasPages())
                        <div class="history-pagination">{{ $rows->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
