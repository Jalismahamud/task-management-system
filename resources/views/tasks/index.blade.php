@extends('app')
@section('title', 'All Tasks')

@section('content')

<div class="mb-6 grid grid-cols-2 sm:grid-cols-4 gap-4">
    @foreach ([
        ['label' => 'Total',       'count' => $stats['total'],       'color' => 'indigo'],
        ['label' => 'Pending',     'count' => $stats['pending'],     'color' => 'yellow'],
        ['label' => 'In Progress', 'count' => $stats['in_progress'], 'color' => 'blue'],
        ['label' => 'Completed',   'count' => $stats['completed'],   'color' => 'green'],
    ] as $stat)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-4 text-center">
            <p class="text-2xl font-bold text-{{ $stat['color'] }}-600">{{ $stat['count'] }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $stat['label'] }}</p>
        </div>
    @endforeach
</div>

<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 mb-6">
    <form method="GET" action="{{ route('tasks.index') }}" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs text-gray-500 mb-1">Search</label>
            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                   placeholder="Search tasks..."
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Status</label>
            <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                <option value="">All Status</option>
                @foreach (['pending' => 'Pending', 'in_progress' => 'In Progress', 'completed' => 'Completed'] as $val => $lbl)
                    <option value="{{ $val }}" {{ ($filters['status'] ?? '') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Priority</label>
            <select name="priority" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                <option value="">All Priority</option>
                @foreach (['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'] as $val => $lbl)
                    <option value="{{ $val }}" {{ ($filters['priority'] ?? '') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-lg transition">Filter</button>
        <a href="{{ route('tasks.index') }}" class="text-sm text-gray-500 hover:text-gray-700 px-2 py-2">Reset</a>
    </form>
</div>

@if ($tasks->isEmpty())
    <div class="text-center py-16 text-gray-400">
        <p class="text-lg">No tasks found.</p>
        <a href="{{ route('tasks.create') }}" class="mt-3 inline-block text-indigo-600 hover:underline text-sm">Create your first task</a>
    </div>
@else
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                <tr>
                    <th class="px-4 py-3 text-left">Title</th>
                    <th class="px-4 py-3 text-left hidden sm:table-cell">Status</th>
                    <th class="px-4 py-3 text-left hidden md:table-cell">Priority</th>
                    <th class="px-4 py-3 text-left hidden md:table-cell">Due Date</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($tasks as $task)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-medium text-gray-800">
                        <a href="{{ route('tasks.show', $task) }}" class="hover:text-indigo-600">
                            {{ $task->title }}
                        </a>
                        @if ($task->isOverdue())
                            <span class="ml-2 text-xs text-red-500 font-medium">Overdue</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 hidden sm:table-cell">
                        @php
                            $statusColors = ['pending' => 'bg-yellow-100 text-yellow-700', 'in_progress' => 'bg-blue-100 text-blue-700', 'completed' => 'bg-green-100 text-green-700'];
                        @endphp
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$task->status] }}">
                            {{ $task->statusLabel() }}
                        </span>
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell">
                        @php
                            $priorityColors = ['low' => 'text-gray-500', 'medium' => 'text-yellow-600', 'high' => 'text-red-600'];
                        @endphp
                        <span class="text-xs font-medium {{ $priorityColors[$task->priority] }}">{{ $task->priorityLabel() }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-500 hidden md:table-cell">
                        {{ $task->due_date ? $task->due_date->format('M d, Y') : '—' }}
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('tasks.edit', $task) }}" class="text-indigo-600 hover:underline text-xs">Edit</a>
                        <form method="POST" action="{{ route('tasks.destroy', $task) }}" class="inline"
                              onsubmit="return confirm('Delete this task?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline text-xs">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $tasks->links() }}</div>
@endif

@endsection
