@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('partials.sidebar')
        <main class="col-md-9">
            <header class="profile-card">
                <div class="profile-img" style="background-image: url({{ asset($profile->img) }})"></div>
                <h1 class="profile-name">{{ Auth::user()->name }}</h1>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#profileModal">
                  Update Profile Image
                </button>
                @include('partials.modals.profile')
            </header>
            <section class="user-posts mt-5">
                @if(count($posts) > 0)
                    @foreach($posts as $post) {{-- Loop through each post --}}
                        <article class="card mb-3">
                            <header class="card-header">
                                <h3 class="float-left">{{ $post->title }}</h3>
                                <form method="POST" action="{{ URL::to('/post/' . $post->id . '/delete') }}"  class="float-right">
                                    @csrf
                                    {{method_field('DELETE')}}
                                    @include ('partials.errors')
                                    <input type="submit" name="delete" type="button" value="Delete" class="btn btn-danger">
                                </form>
                                <a class="float-right btn btn-secondary mr-1" href="{{ URL::to('/post/' . $post->id . '/update') }}">Update</a>
                            </header>
                            <section class="card-body">
                                <p class="card-text">{{ $post->body }}</p>
                            </section>
                            <section class="card-footer">
                                <small class="float-left">Created on <strong>{{ $post->created_at->format('d m Y') }}</strong></small>
                                <small class="text-muted float-right">{{ $post->is_public ? "Public" : "Private" }}</small>
                            </section>
                        </article>
                    @endforeach
                @else
                    <h2>No Posts Yet</h2>
                    <p><a href="{{ url('/create') }}">Click here</a> to get started!</p>
                @endif
            </section>
        </main>
    </div>
</div>
@endsection
