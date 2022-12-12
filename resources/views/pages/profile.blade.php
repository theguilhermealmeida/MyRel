@extends('layouts.app')

@section('title', $user->getName())

@section('content')
    <article class="user" data-id="{{ $user->id }}">
        @if (!$user->ban)

            <div class="post-header">
                <img src={{ $user->photo }}>
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
                        <img class="m-3" id="output"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Edit Profile</button>
                    </div>
                {!! Form::close() !!}
                </div>
            @endif
            <section id="relationships">
                <h2 style="font-size:19px; font-weight:bold;margin-top:30px;">RELATIONSHIPS</h2>
                <hr>
                <?php
                $relationship = $user->relationships()->get();
                $relationship = $relationship->merge($user->relationships2()->get());
                $relationship = $relationship->sortBy('id');
                ?>
                    @each('partials.relationship', $relationship, 'relationship')
            </section>
    </article>
@else
    <section id='relationships'>
        <h3 style="color: Blue">NO RELATIONSHIPS</h2>
    </section>
    @endif


    @if (null !== Auth::user() && ((Auth::user()->id == 0 && !$user->ban) || Auth::user()->id == $user->id))
        <?php
        echo Form::open(['url' => 'api/user/' . $user->id, 'method' => 'delete']);
        echo Form::button('Delete Account', ['type' => 'submit', 'class' => 'btn-danger btn']);
        echo Form::close();
        ?>
    @endif
    @if (null !== Auth::user() && (Auth::user()->id == 0 && $user->ban))
        <?php
        echo Form::open(['url' => 'api/user/' . $user->id, 'method' => 'delete']);
        echo Form::button('Unban Account', ['type' => 'submit', 'class' => 'btn-danger btn']);
        echo Form::close();
        ?>
    @endif


    <script>
        function toggleEditPostPopUp() {
            document.getElementById("popup1").classList.toggle("active");
        }

        function toggleEditProfilePopUp() {
            document.getElementById("popup2").classList.toggle("active");
        }
    </script>
@endsection
