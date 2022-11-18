@extends('layouts.app')

@section('title', $user->name)

@section('content')
<article class="user" data-id="{{ $user->id }}">
<header>
  <h2>{{ $user->name }}</a></h2>
</header>
<img src={{ $user->photo }} >
<div>{{ $user->gender }}</div>
<div>{{ $user->description }}</div>
<section id="relationships">
    <h3 style="color: Blue">RELATIONSHIPS</h2>
  @each('partials.relationship', $user->relationships()->get(), 'relationship')
  @each('partials.relationship', $user->relationships2()->get(), 'relationship')
</section>
</article>
@endsection