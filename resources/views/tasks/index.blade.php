@extends('layouts.app')

@section('title', 'Your tasks')

@section('content')
    <header class="topbar">
        <div class="breadcrumb"><span>PERSONAL SPACE</span><span class="breadcrumb-slash">/</span><span>MY TASKS</span></div>
        <a class="button button-primary" href="{{ route('tasks.create') }}"><span class="button-plus">+</span> New task</a>
    </header>

    <section class="page-heading">
        <div>
            <p class="eyebrow">{{ now()->format('l, F j, Y') }}</p>
            <h1>A little more <em>focused.</em></h1>
            <p class="heading-note">Make room for what matters today.</p>
        </div>
        <div class="completion-stamp" aria-label="{{ $counts['completed'] }} tasks completed">
            <span class="stamp-number">{{ str_pad((string) $counts['completed'], 2, '0', STR_PAD_LEFT) }}</span>
            <span class="stamp-caption">DONE<br>SO FAR</span>
        </div>
    </section>

    @if (session('success'))
        <div class="notice" role="status">{{ session('success') }}</div>
    @endif

    <section class="task-section" aria-labelledby="task-list-title">
        <div class="section-heading">
            <div>
                <h2 id="task-list-title">{{ $filter === 'all' ? 'Your list' : ucfirst($filter) . ' tasks' }}</h2>
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
                <span class="empty-mark">✳</span>
                <h3>{{ $filter === 'all' ? 'A clear page.' : 'Nothing here yet.' }}</h3>
                <p>{{ $filter === 'all' ? 'Add a task and give your day a starting point.' : 'Tasks will show up here when their status matches.' }}</p>
                @if ($filter === 'all')
                    <a class="button button-outline" href="{{ route('tasks.create') }}">Create your first task</a>
                @endif
            </div>
        @else
            <div class="task-list">
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
                        <div class="task-meta">
                            @if ($task->due_date)
                                <span class="due-date {{ $isOverdue ? 'is-overdue' : '' }}">
                                    <span class="due-dot"></span>{{ $isOverdue ? 'Overdue · ' : '' }}{{ $task->due_date->format('M j, Y') }}
                                </span>
                            @else
                                <span class="no-date">No deadline</span>
                            @endif
                            <span class="status-label {{ $task->status === 'completed' ? 'label-completed' : 'label-pending' }}">{{ ucfirst($task->status) }}</span>
                        </div>
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

    <footer class="page-footer"><span>DAYMARK / PERSONAL TASK MANAGER</span><span>Progress, not perfection.</span></footer>
@endsection