@extends('app')
@section('title', 'Create Task')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="mb-5 flex items-center gap-3">
        <a href="{{ route('tasks.index') }}" class="text-gray-400 hover:text-gray-600 text-sm">&larr; Back</a>
        <h1 class="text-xl font-bold text-gray-800">Create New Task</h1>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <form method="POST" action="{{ route('tasks.store') }}">
            @csrf
            @include('tasks._form')
            <div class="mt-6 flex gap-3">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition">
                    Create Task
                </button>
                <a href="{{ route('tasks.index') }}"
                   class="border border-gray-300 text-gray-600 hover:bg-gray-50 text-sm px-5 py-2 rounded-lg transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
