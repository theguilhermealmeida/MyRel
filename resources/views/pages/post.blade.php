@extends('layouts.app')

@section('title', $post->name)

@section('content')
<article class="post" data-id="{{ $post->id }}">
<header>
  <h2>{{ $post->id }}</a></h2>
</header>
<div>{{ $post->text }}</div>
<img src={{ $post->photo }} > 
<div>{{ $post->date }}</div>
</article>
@endsection