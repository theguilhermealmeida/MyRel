@extends('layouts.app')

@section('title', 'Posts')

@section('content')
@if (Auth::check())
<button id="toggle_create_post" class="mx-auto btn btn-primary btn-lg">Create a new Post</button>
@endif

<hr>

<div style="display:none" id="create_post" class="post card mb-3">
  {!!Form::open(['url' => 'api/posts', 'method' => 'put','enctype' => 'multipart/form-data','class'=>'form-horizontal','id'=>'create_post_form']) !!}
  {!! Form::token() !!}
    <div class="form-group">
        <div>
        <textarea required name="text" onkeyup="countChars(this,document.getElementById('charNumText'),280);" placeholder="Share your thoughts with us..." maxlength="280" class="form-control" rows="5"></textarea>
        <p id="charNumText">0 characters</p>
        </div>
    </div>
    <div class="form-group">
        <div>
          <input style="color:black;background-color:white" id="newpost" type="file" accept="image/*" class="form-control" name="image" onchange="loadFile(event)">
          <img class="m-3" id="output" alt=""/>
        </div>
    </div>
    <div class="form-group">
        <div>
        <select required name ="visibility"  class="selectpicker" data-width="60%">
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