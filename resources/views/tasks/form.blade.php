@extends('layouts.app')

@php($isEditing = $task->exists)

@section('title', $isEditing ? 'Edit task' : 'New task')

@section('content')
    <header class="topbar">
        <div class="breadcrumb"><a href="{{ route('tasks.index') }}">MY TASKS</a><span class="breadcrumb-slash">/</span><span>{{ $isEditing ? 'EDIT TASK' : 'NEW TASK' }}</span></div>
        <a class="button button-quiet" href="{{ route('tasks.index') }}">Back to tasks</a>
    </header>

    <section class="form-page">
        <p class="eyebrow">{{ $isEditing ? 'MAKE A CHANGE' : 'MAKE A PLAN' }}</p>
        <h1>{{ $isEditing ? 'Refine the details.' : 'What’s on your mind?' }}</h1>
        <p class="heading-note">{{ $isEditing ? 'Update your task details below.' : 'Give your next task a name and a place in your day.' }}</p>

        @if ($errors->any())
            <div class="error-summary" role="alert">
                <strong>Please check the fields below.</strong>
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form class="task-form" action="{{ $isEditing ? route('tasks.update', $task) : route('tasks.store') }}" method="POST">
            @csrf
            @if ($isEditing) @method('PUT') @endif

            <div class="form-field">
                <label for="task_name">Task name <span class="required-mark">*</span></label>
                <input id="task_name" name="task_name" type="text" value="{{ old('task_name', $task->task_name) }}" maxlength="120" required autofocus placeholder="e.g. Prepare the project presentation">
                @error('task_name')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-field">
                <label for="description">Details <span class="optional-mark">OPTIONAL</span></label>
                <textarea id="description" name="description" rows="4" maxlength="2000" placeholder="Add a note or a few useful details…">{{ old('description', $task->description) }}</textarea>
                @error('description')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-grid">
                <div class="form-field">
                    <label for="due_date">Due date <span class="optional-mark">OPTIONAL</span></label>
                    <input id="due_date" name="due_date" type="date" value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}">
                    @error('due_date')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-field">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <option value="pending" @selected(old('status', $task->status ?: 'pending') === 'pending')>Pending</option>
                        <option value="completed" @selected(old('status', $task->status ?: 'pending') === 'completed')>Completed</option>
                    </select>
                    @error('status')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-actions">
                <button class="button button-primary" type="submit">{{ $isEditing ? 'Save changes' : 'Add task' }}</button>
                <a class="button button-quiet" href="{{ route('tasks.index') }}">Cancel</a>
            </div>
        </form>
    </section>
@endsection