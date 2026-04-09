@extends('layouts.app')
@section('title', $task->title)

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="mb-5 flex items-center gap-3">
        <a href="{{ route('tasks.index') }}" class="text-gray-400 hover:text-gray-600 text-sm">&larr; Back</a>
        <h1 class="text-xl font-bold text-gray-800">Task Details</h1>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">

        <div>
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Title</p>
            <p class="text-gray-800 font-semibold text-lg">{{ $task->title }}</p>
        </div>

        @if ($task->description)
        <div>
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Description</p>
            <p class="text-gray-700 text-sm leading-relaxed">{{ $task->description }}</p>
        </div>
        @endif

        <div class="grid grid-cols-3 gap-4 pt-2">
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Status</p>
                @php
                    $statusColors = ['pending' => 'bg-yellow-100 text-yellow-700', 'in_progress' => 'bg-blue-100 text-blue-700', 'completed' => 'bg-green-100 text-green-700'];
                @endphp
                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$task->status] }}">
                    {{ $task->statusLabel() }}
                </span>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Priority</p>
                <p class="text-sm font-medium text-gray-700">{{ $task->priorityLabel() }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Due Date</p>
                <p class="text-sm text-gray-700">
                    {{ $task->due_date ? $task->due_date->format('M d, Y') : '—' }}
                    @if ($task->isOverdue())
                        <span class="text-red-500 text-xs ml-1">(Overdue)</span>
                    @endif
                </p>
            </div>
        </div>

        <div class="pt-4 flex gap-3 border-t border-gray-100">
            <a href="{{ route('tasks.edit', $task) }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-lg transition">
                Edit
            </a>
            <form method="POST" action="{{ route('tasks.destroy', $task) }}"
                  onsubmit="return confirm('Are you sure?')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="border border-red-300 text-red-600 hover:bg-red-50 text-sm px-4 py-2 rounded-lg transition">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

@endsection


