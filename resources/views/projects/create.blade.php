@extends('layouts.app')

@section('content')

    <h1>Create</h1>
    <form method="POST" action="/projects">
        @csrf
        <div class="form-group">
        <label for="title">Title</label>
        <input class="form-control" type="text" name="title">
        </div>
        <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control" name="description" cols="30" rows="10"></textarea>
        </div>
        <button class="btn btn-primary" type="submit">Create</button>
        <a href="/projects" class="btn btn-danger">Cancel</a>
    </form>

@endsection
