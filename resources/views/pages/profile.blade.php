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
        </div>
        
            
        <style>
            .form-holder{
                margin-top:30px;
            }
            .form-holder form{
                display: flex;
                flex-direction:column;
            }
            .form-holder form textarea{
               margin-bottom:10px;
            }
        </style>

            @if ($user->id == Auth::user()->id)
                <button class="btn-primary btn" onclick="toggleEditProfilePopUp()">Edit Profile</button>
                <div class="popup" id="popup2">
                    <div class='overlay'></div>
                    <div class='content'>
                        <div class="close-btn" onclick="toggleEditProfilePopUp()">&times;
                        </div>
                        <div class="form-holder">
                        <?php
                        echo Form::open(['url' => 'api/user/' . $user->id, 'method' => 'post']);
                        echo 'Name';
                        echo Form::textarea('name', $user->getName(), ['rows' => 1]);
                        echo 'Gender';
                        echo Form::textarea('gender', $user->gender, ['rows' => 1]);
                        echo 'Description';
                        echo Form::textarea('description', $user->description);
                        echo Form::button('Edit Profile', array('type' => 'submit', 'class' => 'btn btn-primary', 'style' => 'margin-top:10px;'));
                        echo Form::close();
                        ?>
                        </div>
                    </div>
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
                    <!--@each('partials.relationship', $user->relationships()->get(), 'relationship')
                    @each('partials.relationship', $user->relationships2()->get(), 'relationship')-->
            </section>
    </article>
@else
    <section id='relationships'>
        <h3 style="color: Blue">NO RELATIONSHIPS</h2>
    </section>
    @endif



    <?php
                echo Form::open(['url' => 'api/user/' . $user->id, 'method' => 'delete']);
                echo Form::button('Delete Account', array('type' => 'submit', 'class' => 'btn-danger btn'));
                echo Form::close();
                ?>

                
    <script>

function toggleEditPostPopUp(){
  document.getElementById("popup1").classList.toggle("active");
}
function toggleEditProfilePopUp(){
  document.getElementById("popup2").classList.toggle("active");
}
    </script>
@endsection
