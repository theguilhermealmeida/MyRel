@extends('layouts.app')

@section('title', 'Notifications')

@section('content')

{{-- @php
    $notifications->sortBy('date');
@endphp --}}
<div class='mark-all-as-read-section'>
{!!Form::open(['route' => ['notifications.markAllAsRead', 'id' => Auth::user()->id], 'method' => 'get','enctype' => 'multipart/form-data','class'=>'form-horizontal','id'=>'create_post_form']) !!}
{!! Form::token() !!}
    <button type="submit" class='mark-all-as-read'>
    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-check-circle mr-2" viewBox="0 0 16 16">
        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
        <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
      </svg>
        Mark all as read.
    </button>
    {{-- <div class="all-read-text mb-3"> Mark all as read.</div> --}}
</div>
{!!Form::close()!!}
<hr>
@if($notifications->count() == 0)
<h3 class="no-notifications">No Notifications</h3>
@else
    @php
        $unread_notifications = $notifications->where('read', 0);
        $read_notifications = $notifications->where('read', 1);
    @endphp

<h3 class="no-notifications">Unread</h3>
    @each('partials.notification', $unread_notifications, 'notification') 
<h3 class="no-notifications">Read</h3>
    @each('partials.notification', $read_notifications, 'notification') 
@endif
  
@endsection