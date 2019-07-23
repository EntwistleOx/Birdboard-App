@extends('layouts.app')

@section('content')

    <header class="flex items-center mb-3 py-4">
        <div class="flex justify-between items-end w-full">
            <p class="text-default text-sm font-normal">
               <a href="/projects" class="text-default text-sm font-normal no-underline">My Proyects</a> / {{$project->title}}
            </p>
            <div class="flex items-center">
                @foreach ($project->members as $member)
                    <img
                        src="{{gravatar_url($member->email)}}"
                        alt="{{ $member->name }}'s avatar'"
                        class="rounded-full w-8 mr-2">
                @endforeach

                <img
                    src="{{gravatar_url($project->owner->email)}}"
                    alt="{{ $project->owner->name }}'s avatar'"
                    class="rounded-full w-8 mr-2">

                <a href="{{ $project->path() . '/edit' }}" class="button ml-4">Edit Project</a>
            </div>
        </div>
    </header>

    <main>
        <div class="lg:flex -mx-3">
            <div class="lg:w-3/4 px-3">
                <div class="mb-8">
                    <h2 class="text-lg text-default font-normal mb-3">Tasks</h2>
                    {{-- tasks --}}
                    @foreach ($project->tasks as $task)
                    <div class="card mb-3">
                        <form action="{{ $task->path() }}" method="post">
                            @csrf
                            @method('PATCH')
                            <div class="flex">
                                    <input class="w-full bg-card {{ $task->completed ? 'text-default' : ''}}" type="text" name="body" value="{{ $task->body }}">
                                    <input type="checkbox" name="completed" onchange="this.form.submit()" {{ $task->completed ? 'checked' : ''}}>
                            </div>
                        </form>
                    </div>
                    @endforeach
                    <div class="card mb-3">
                        <form action="{{ $project->path() . '/tasks' }}" method="pos t">
                            @csrf
                            <input class="w-full bg-card" type="text" name="body" placeholder="Add a new tasks...">
                        </form>
                    </div>
                </div>
                <div>
                    <h2 class="text-lg text-default font-normal">General notes</h3>
                    {{-- general notes --}}
                    <form action="{{ $project->path() }}" method="post">
                        @csrf
                        @method('PATCH')
                        <textarea name="notes" class="text-default w-full card mb-4"
                        placeholder="Anything special that you want to make a note of?"
                        >{{ $project->notes }}</textarea>
                        <button type="submit" class="button">Save</button>
                    </form>

                    @include('errors')

                </div>
            </div>
            <div class="lg:w-1/4 px-3 lg:py-8">
                @include('projects.card')
                @include('projects.activity.card')
                @can('manage', $project)
                    @include('projects.invite')
                @endcan
            </div>
        </div>
    </main>

@endsection
