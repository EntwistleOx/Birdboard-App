@extends('layouts.app')

@section('content')

    <header class="flex items-center mb-3 py-4">
        <div class="flex justify-between w-full items-end">
            <p class="text-gray-500 text-sm font-normal">
               <a href="/projects" class="text-gray-500 text-sm font-normal no-underline">My Proyects</a> / {{$project->title}}
            </p>
            <a href="/projects/create" class="button">New Project</a>
        </div>
    </header>

    <main>
        <div class="lg:flex -mx-3">
            <div class="lg:w-3/4 px-3">
                <div class="mb-8">
                    <h2 class="text-lg text-gray-500 font-normal mb-3">Tasks</h2>
                    {{-- tasks --}}
                    @foreach ($project->tasks as $task)
                        <div class="card mb-3">{{ $task->body }}</div>
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
                    <textarea class="card w-full min-h-150 ">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Maxime eum officia labore optio aperiam modi eos, ad cum voluptates voluptas, repudiandae dicta sit magnam quibusdam quis aliquid officiis velit in.</textarea>
                </div>
            </div>
            <div class="lg:w-1/4 px-3 mt-10">
                @include('projects.card')
            </div>
        </div>
    </main>

@endsection
