@extends('layouts.app')

@section('title', 'Relationships')

@section('content')


<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="friends-tab" data-toggle="tab" href="#friends" role="tab" aria-controls="friends" aria-selected="true">Friends</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="close_friends-tab" data-toggle="tab" href="#close_friends" role="tab" aria-controls="close_friends" aria-selected="false">Close Friends</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="family-tab" data-toggle="tab" href="#family" role="tab" aria-controls="family" aria-selected="false">Family</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="requests-tab" data-toggle="tab" href="#requests" role="tab" aria-controls="requests" aria-selected="false">Requests</a>
    </li>
  </ul>
  
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="friends" role="tabpanel" aria-labelledby="friends-tab">
        @each('partials.relationship', $friends, 'relationship') 
    </div>
    <div class="tab-pane fade" id="close_friends" role="tabpanel" aria-labelledby="close_friends-tab">
        @each('partials.relationship', $close_friends, 'relationship') 
    </div>
    <div class="tab-pane fade" id="family" role="tabpanel" aria-labelledby="family-tab">
        @each('partials.relationship', $family, 'relationship') 
    </div>
    <div class="tab-pane fade" id="requests" role="tabpanel" aria-labelledby="requests-tab">
        @each('partials.relationship', $pending_relationships, 'relationship') 
    </div>
  </div>
  
@endsection