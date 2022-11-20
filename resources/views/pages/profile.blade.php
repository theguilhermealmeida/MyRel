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
                <button onclick="toggleEditProfilePopUp()">Edit Profile</button>
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
                        echo Form::button('Edit Profile', ['type' => 'submit']);
                        echo Form::close();
                        ?>
                    </div>
                </div>

                <?php
                echo Form::open(['url' => 'api/user/' . $user->id, 'method' => 'delete']);
                echo Form::button('Delete Account', ['type' => 'submit']);
                echo Form::close();
                ?>
            @endif

            <section id="relationships">
                <h3 style="color: Blue">RELATIONSHIPS</h2>
                    @each('partials.relationship', $user->relationships()->get(), 'relationship')
                    @each('partials.relationship', $user->relationships2()->get(), 'relationship')
            </section>
    </article>
@else
    <section id='relationships'>
        <h3 style="color: Blue">NO RELATIONSHIPS</h2>
    </section>
    @endif
@endsection
