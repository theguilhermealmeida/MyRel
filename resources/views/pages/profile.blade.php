@extends('layouts.app')

@section('title', $user->getName())

@section('content')
    <article class="user" data-id="{{ $user->id }}">
        <header>
            <h2>{{ $user->getName() }}</a></h2>
        </header>

        @if (!$user->ban)
            <img src={{ $user->photo }}>
            <div>{{ $user->gender }}</div>
            <div>{{ $user->description }}</div>

            @if ($user->id == Auth::user()->id)
                <button class="btn-primary btn" onclick="toggleEditProfilePopUp()">Edit Profile</button>
                <div class="popup" id="popup2">
                    <div class='overlay'></div>
                    <div class='content'>
                        <div class="close-btn" onclick="toggleEditProfilePopUp()">&times;
                        </div>
                        <?php
                        echo Form::open(['url' => 'api/user/' . $user->id, 'method' => 'post']);
                        echo 'Name';
                        echo Form::textarea('name', $user->getName());
                        echo 'Gender';
                        echo Form::textarea('gender', $user->gender);
                        echo 'Description';
                        echo Form::textarea('description', $user->description);
                        echo Form::button('Edit Profile', ['type' => 'submit'], ['class' => 'btn-primary btn']);
                        echo Form::close();
                        ?>
                    </div>
                </div>

                <?php
                echo Form::open(['url' => 'api/user/' . $user->id, 'method' => 'delete']);
                echo Form::button('Delete Account', ['type' => 'submit'], ['class' => 'btn-danger btn']);
                echo Form::close();
                ?>
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
@endsection
