@extends('layouts.app')

@section('title', 'Your tasks')

@section('content')
    <header class="topbar">
        <div class="breadcrumb"><span>WORKSPACE</span><span class="breadcrumb-slash">/</span><span>TASKS</span></div>
        <a class="button button-primary" href="{{ route('tasks.create') }}"><span class="button-plus">+</span> Add task</a>
    </header>

    <section class="page-heading">
        <div>
            <h1>My tasks</h1>
            <p class="heading-note">{{ now()->format('l, F j, Y') }} <span aria-hidden="true">·</span> Keep track of what needs doing.</p>
        </div>
    </section>

    @if (session('success'))
        <div class="notice" role="status">{{ session('success') }}</div>
    @endif

    <section class="summary-strip" aria-label="Task totals">
        <div class="summary-item"><span>Total tasks</span><strong>{{ $counts['all'] }}</strong></div>
        <div class="summary-item"><span>Pending</span><strong>{{ $counts['pending'] }}</strong></div>
        <div class="summary-item"><span>Completed</span><strong>{{ $counts['completed'] }}</strong></div>
    </section>

    <section class="task-section" aria-labelledby="task-list-title">
        <div class="section-heading">
            <div>
                <h2 id="task-list-title">{{ $filter === 'all' ? 'Task list' : ucfirst($filter) . ' tasks' }}</h2>
                <span class="section-subtitle">{{ $tasks->count() }} {{ Str::plural('task', $tasks->count()) }}</span>
            </div>
            <div class="filter-tabs" role="group" aria-label="Filter tasks">
                <a class="filter-tab {{ $filter === 'all' ? 'selected' : '' }}" href="{{ route('tasks.index') }}">All</a>
                <a class="filter-tab {{ $filter === 'pending' ? 'selected' : '' }}" href="{{ route('tasks.index', ['status' => 'pending']) }}">Pending</a>
                <a class="filter-tab {{ $filter === 'completed' ? 'selected' : '' }}" href="{{ route('tasks.index', ['status' => 'completed']) }}">Completed</a>
            </div>
        </div>

        @if ($tasks->isEmpty())
            <div class="empty-state">
                <h3>{{ $filter === 'all' ? 'No tasks yet' : 'No matching tasks' }}</h3>
                <p>{{ $filter === 'all' ? 'Add a task to start organizing your work.' : 'Try another filter or add a task.' }}</p>
                @if ($filter === 'all')
                    <a class="button button-outline" href="{{ route('tasks.create') }}">Add your first task</a>
                @endif
            </div>
        @else
            <div class="task-list">
                <div class="task-list-heading" aria-hidden="true">
                    <span></span><span>Task</span><span>Due date</span><span>Status</span><span></span>
                </div>
                @foreach ($tasks as $task)
                    @php
                        $isOverdue = $task->status === 'pending' && $task->due_date && $task->due_date->lt(today());
                    @endphp
                    <article class="task-row {{ $task->status === 'completed' ? 'task-row-completed' : '' }}">
                        <form class="status-form" action="{{ route('tasks.status', $task) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="{{ $task->status === 'completed' ? 'pending' : 'completed' }}">
                            <button class="status-toggle {{ $task->status === 'completed' ? 'is-complete' : '' }}" type="submit" aria-label="Mark {{ $task->task_name }} as {{ $task->status === 'completed' ? 'pending' : 'completed' }}">
                                @if ($task->status === 'completed')<span>✓</span>@endif
                            </button>
                        </form>
                        <div class="task-copy">
                            <h3>{{ $task->task_name }}</h3>
                            @if ($task->description)
                                <p>{{ $task->description }}</p>
                            @endif
                        </div>
                        <span class="due-date {{ $isOverdue ? 'is-overdue' : '' }}">
                            @if ($task->due_date)
                                {{ $isOverdue ? 'Overdue · ' : '' }}{{ $task->due_date->format('M j, Y') }}
                            @else
                                <span class="no-date">No due date</span>
                            @endif
                        </span>
                        <span class="status-label {{ $task->status === 'completed' ? 'label-completed' : 'label-pending' }}">{{ ucfirst($task->status) }}</span>
                        <div class="task-actions">
                            <a class="text-action" href="{{ route('tasks.edit', $task) }}">Edit</a>
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Delete this task? This cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button class="text-action text-action-delete" type="submit">Delete</button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    <footer class="page-footer"><span>DAYMARK · PERSONAL TASK MANAGER</span><span>{{ $counts['all'] }} {{ Str::plural('task', $counts['all']) }}</span></footer>
@endsection