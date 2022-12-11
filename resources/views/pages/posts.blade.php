@extends('layouts.app')

@section('title', 'Posts')

@section('content')
<h1>Create a new Post</h1>
<hr>

<div class="post card mb-3">
  {!!Form::open(['url' => 'api/posts', 'method' => 'put','enctype' => 'multipart/form-data','class'=>'form-horizontal']) !!}
  {!! Form::token() !!}
    <div class="form-group">
        <div>
        <textarea name="text" placeholder="Share your thoughts with us..." maxlength="180" class="form-control" rows="3"></textarea>
        </div>
    </div>
    <div class="form-group">
        <div>
        <input type="file" name="image">
        </div>
    </div>
    <div class="form-group">
        <div>
        <select required name ="visibility" class="selectpicker" multiple data-width="60%">
          <option>Close Friends</option>
          <option>Friends</option>
          <option>Family</option>
          <option>Strangers</option>
        </select>
        <button type="submit" class="btn btn-primary float-right">Post</button>
        </div>
    </div>
  {!! Form::close() !!}
</div>


<h1>Your Feed</h1>
<hr>
<section id="posts">
  @each('partials.post', $posts, 'post')
</section>

@endsection