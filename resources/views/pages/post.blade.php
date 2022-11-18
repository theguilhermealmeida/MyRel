@extends('layouts.app')

@section('title', $post->name)

@section('content')
<article class="post" data-id="{{ $post->id }}" style="border-style: solid;color: Tomato">
<header>
  <h2 onclick="location.href='/user/{{ $post->user()->get()[0]->id }}';" style="cursor:pointer;" >{{ $post->user()->get()[0]->name }}</a><img src={{ $post->user()->get()[0]->photo }} ></h2>
</header>
<div style="border-style: solid;color: Blue" >{{ $post->text }}</div>
<img src={{ $post->photo }} > 
<div>{{ $post->date }}</div>
<section id="comments" style="border-style: solid;color: Purple">
  <h2 style="color: Blue">COMMENTS</h2>
  @each('partials.comment', $post->comments()->get(), 'comment')
</section>
</article>
@endsection