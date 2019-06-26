@extends('layouts.app')

@section('content')

    <div style="display: flex; align-items:center">
        <a href="/projects/create" class="btn btn-primary">Create Project</a>
    </div>
    <ul>
        @forelse ($projects as $project)
            <li>
                <a href="{{ $project->path() }}">{{$project->title}}</a>
            </li>
        @empty
            <li>No hay datos</li>
        @endforelse
    </ul>

@endsection
