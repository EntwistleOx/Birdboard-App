@extends('layouts.app')

@section('content')

    <header class="flex items-center mb-3 py-4">
        <div class="flex justify-between w-full items-end">
            <p class="text-gray-500 text-sm font-normal">
               <a href="/projects" class="text-gray-500 text-sm font-normal no-underline">My Proyects</a> / {{$project->title}}
            </p>
            <a href="{{ $project->path() . '/edit' }}" class="button">Edit Project</a>
        </div>
    </header>

    <main>
        <div class="lg:flex -mx-3">
            <div class="lg:w-3/4 px-3">
                <div class="mb-8">
                    <h2 class="text-lg text-gray-500 font-normal mb-3">Tasks</h2>
                    {{-- tasks --}}
                    @foreach ($project->tasks as $task)
                    <div class="card mb-3">
                        <form action="{{ $task->path() }}" method="post">
                            @csrf
                            @method('PATCH')
                            <div class="flex">
                                    <input class="w-full {{ $task->completed ? 'text-gray-600' : ''}}" type="text" name="body" value="{{ $task->body }}">
                                    <input type="checkbox" name="completed" onchange="this.form.submit()" {{ $task->completed ? 'checked' : ''}}>
                            </div>
                        </form>
                    </div>
                    @endforeach
                    <div class="card mb-3">
                        <form action="{{ $project->path() . '/tasks' }}" method="post">
                            @csrf
                            <input class="w-full" type="text" name="body" placeholder="Add a new tasks...">
                        </form>
                    </div>
                </div>
                <div>
                    <h2 class="text-lg text-gray-500 font-normal">General notes</h3>
                    {{-- general notes --}}
                    <form action="{{ $project->path() }}" method="post">
                        @csrf
                        @method('PATCH')
                        <textarea name="notes" class="card w-full mb-4"
                        placeholder="Anything special that you want to make a note of?"
                        >{{ $project->notes }}</textarea>
                        <button type="submit" class="button">Save</button>
                    </form>


                    @if ($errors->any())
                    <div class="field mt-6">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm text-red">{{ $error }}</li>
                        @endforeach
                    </div>
                @endif

                </div>
            </div>
            <div class="lg:w-1/4 px-3 mt-10">
                @include('projects.card')
            </div>
        </div>
    </main>

@endsection
