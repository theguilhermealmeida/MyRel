@extends('layouts.app')

@section('title', $user->getName())

@section('content')
    <article class="user" data-id="{{ $user->id }}">
        @if (!$user->ban)

            <div class="post-header">
                <img src={{ $user->photo }} alt=id={{ $user->id }}>
                <div class="post-header-info" style="margin-left:30px;">
                    <h2>{{ $user->getName() }}</a></h2>
                    <div>{{ $user->gender }}</div>
                    <div>{{ $user->description }}</div>
                </div>
                @if (null !== Auth::user() && (Auth::user()->id == 0 || Auth::user()->id == $user->id))
                    <button id="toggle_edit_profile" class="mx-auto btn btn-primary btn-lg">Edit Profile</button>
                @endif
            </div>

            @if (null !== Auth::user() && (Auth::user()->id == 0 || Auth::user()->id == $user->id))

                <div  style="display:none" id="edit_profile" class="post card mb-3">
                {!!Form::open(['url' => 'api/user/' . $user->id, 'method' => 'post','enctype' => 'multipart/form-data','class'=>'form-horizontal','id'=>'edit_profile_form']) !!}
                {!! Form::token() !!}
                    <div class="form-group">
                        <div>
                        <label for="edit_profile_name">Name</label>
                        <textarea required name="name" onkeyup="countChars(this,document.getElementById('charNumName'),30);" maxlength="30" id="edit_profile_name" class="form-control" rows="1">{{ $user->getName() }}</textarea>
                        <p id="charNumName">0 characters</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <div>
                        <label for="edit_profile_gender">Gender</label>
                        <select required name ="gender" id="edit_profile_gender" class="selectpicker" data-gender="{{$user->gender}}">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div>
                        <label for="edit_profile_description">Description</label>
                        <textarea required name="description" onkeyup="countChars(this,document.getElementById('charNumDescription'),280);" maxlength="280" id="edit_profile_description" class="form-control" rows="5">{{ $user->description }}</textarea>
                        <p id="charNumDescription">0 characters</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <div>
                        <label for="edit_profile_picture">Profile Picture</label>
                        <input style="color:black;background-color:white" id="edit_profile_picture" type="file" accept="image/*" class="form-control" name="image" onchange="loadFile(event)">
                        <img class="m-3" id="output" alt=""/>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Edit Profile</button>
                    </div>
                    
                {!! Form::close() !!}
                {!!Form::open(['route' => ['password.change', 'id' => $user->id], 'method' => 'get','enctype' => 'multipart/form-data','class'=>'form-horizontal','id'=>'change_password_button']) !!}
                {!! Form::token() !!}
                    <button class='btn btn-link'>change password</button>
                {!! Form::close() !!}
                </div>
            @endif


            @if (Auth::check() && ($user->getRelationship(Auth::user()->id, $user->id) != null || $user->getRelationship($user->id, Auth::user()->id) != null))
                @php
                    $rel = $user->getRelationship(Auth::user()->id, $user->id);
                    if ($rel == null) {$rel = $user->getRelationship($user->id, Auth::user()->id);}
                @endphp

                @if ($rel['pivot']['state'] == 'accepted')
                    {!!Form::open(['url' => 'api/relationships/' . $rel['pivot']['id'], 'method' => 'delete','enctype' => 'multipart/form-data','class'=>'form-horizontal','id'=>'remove_rel_form']) !!}
                    {!! Form::token() !!}
                        <button type="submit" class="badge badge-pill badge-light" name="remove_relationship"> 
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                            </svg>
                            <div class="mx-2">{{$rel['pivot']['type']}}</div>
                        </button>
                    {!! Form::close() !!}
                @elseif ($rel['pivot']['state'] == 'pending')
                {{-- @php
                    echo 'sender_id '. $rel->['pivot']['user_id'];
                    echo 'recipient_id '. $rel->['pivot']['related_id'];
                @endphp --}}
                    {{-- @if (Auth::user()->id == $rel->user_id && user()->id == $rel->related_id) --}}
                    {{-- @php
                        foreach($rel as $r){
                            echo $r;
                        }
                    @endphp --}}
                    @if ($rel['pivot']['user_id'] == Auth::user()->id && $rel['pivot']['related_id'] == $user->id)
                    {!!Form::open(['url' => 'api/relationships/' . $rel['pivot']['id'], 'method' => 'delete','enctype' => 'multipart/form-data','class'=>'form-horizontal','id'=>'remove_rel_form']) !!}
                    {!! Form::token() !!}
                        <button type="submit" class="badge badge-pill badge-light" name="remove_relationship"> 
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                            </svg>
                            <div class="mx-2">Pending</div>
                        </button>
                    {!! Form::close() !!}
                    @elseif ($rel['pivot']['user_id'] == $user->id && $rel['pivot']['related_id'] == Auth::user()->id)
                    <div class="btn-group" role="group" aria-label="Basic example">

                        {!! Form::open(['url' => 'api/relationships/' . $rel['pivot']['id'], 'method' => 'post']) !!}
                                <button type="submit" class="btn btn-primary btn-sm mr-2">Accept Request</button>
                        {!! Form::close() !!}
                        {!! Form::open(['url' => 'api/relationships/' . $rel['pivot']['id'], 'method' => 'delete']) !!}
                                <button type="submit" class="btn btn-secondary btn-sm">Decline Request</button>
                        {!! Form::close() !!}
                    </div>
                    @endif
                    {{-- @endif --}}

                @endif

            @elseif (Auth::check() && Auth::user()->id != $user->id && $user->getRelationship(Auth::user()->id, $user->id) == null)
                {!!Form::open(['url' => 'api/relationships/' . $user->id, 'method' => 'put','enctype' => 'multipart/form-data','class'=>'form-horizontal','id'=>'request_relationship_form']) !!}
                {!! Form::token() !!}
                    <div class="form-group">

                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownRequest" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Request Relationship
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <div class="form-group">
                                <button type="submit" class="dropdown-item" name="type" value="Friends">Friend</button>
                                <button type="submit" class="dropdown-item" name="type" value="Close Friends">Close Friend</button>
                                <button type="submit" class="dropdown-item" name="type" value="Family">Family</button>
                            </div>
                        </div>
                    </div>
                    </div>
                {!! Form::close() !!}
            @endif

               
            <div class="mx-10"></div>
                

            <section id="relationships">
                @if (Auth::check() && Auth::user()->id == $user->id)
                <a class='btn btn-link btn-lg pl-2' href="../relationships">
                    <span>Relationships</span>
                    <div class="ml-2"> {{$relationships->where('pivot.state','accepted')->count()}}</div>
                </a>
                @endif
                @if ((Auth::check() && Auth::user()->id != $user->id) ||  Auth::check() == null)
                <a class='btn btn-link btn-lg pl-2'>
                    <span>Relationships</span>
                    <div class="ml-2"> {{$relationships->where('pivot.state', 'accepted')->count()}}</div>
                </a>
                @endif
                <div class="relationship-holder">
                    <span class="reaction-label">
                        <span>Close Friends:</span>
                        {{$close_friends->count()}}
                    </span>
                    <span class="reaction-label">
                        <span>Friends:</span>
                        {{$friends->count()}}
                    </span>
                    <span class="reaction-label">
                        <span>Family:</span>
                        {{$family->count()}}
                    </span>
                </div>
            </section>
    </article>
@else
    <section id='relationships'>
        <h3 style="color: Blue">NO RELATIONSHIPS</h2>
    </section>
    @endif

    <hr>

    @if (null !== Auth::user() && ((Auth::user()->id == 0 && !$user->ban) || Auth::user()->id == $user->id))
        <h3 style="color: red">Danger Zone</h2>
        <?php
        echo Form::open(['url' => 'api/user/ban/' . $user->id, 'method' => 'post']);
        echo Form::button('Delete Account', ['type' => 'submit', 'class' => 'btn-danger btn']);
        echo Form::close();
        ?>
    @endif
    @if (null !== Auth::user() && (Auth::user()->id == 0 && $user->ban))
        <?php
        echo Form::open(['url' => 'api/user/ban/' . $user->id, 'method' => 'post']);
        echo Form::button('Unban Account', ['type' => 'submit', 'class' => 'btn-danger btn']);
        echo Form::close();
        ?>
    @endif
    <hr>
            <h1>My Feed</h1>
        <hr>
        <section id="posts">
        @each('partials.post', $posts, 'post')
        </section>
    <script>
        function toggleEditPostPopUp() {
            document.getElementById("popup1").classList.toggle("active");
        }

        function toggleEditProfilePopUp() {
            document.getElementById("popup2").classList.toggle("active");
        }
    </script>
    
@endsection


