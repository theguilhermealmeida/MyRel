<div class="post card" data-id="{{ $post->id }}">
        <div class="post-header"> 
              <img src={{ $post->user()->get()[0]->photo }} class="post-profile-pic" alt=id={{ $post->user()->get()[0]->id }}>
              <div class="post-header-info">
                  <h3><a href="/user/{{ $post->user()->get()[0]->id }}">{{ $post->user()->get()[0]->getName() }}</a></h3>
                  <p>{{ $post->date }}</p>
              </div>
              @if ($post->visibility ===NULL)
                <div class="ml-auto badge badge-pill badge-info">Strangers</div>
              @else
                <div class="ml-auto badge badge-pill badge-info">{{ $post->visibility }}</div>
              @endif
          </div>
          <a class="post-body" href="/posts/{{ $post->id }}">
              <p style="color:white;text-decoration: none; background-color: none;">{{ $post->text }}</p>
              @if($post->photo !== null)
              <div style="
                    width: 100%;
                    border-radius: 1em;
                    height: 22em;
                    background-image: url({{ $post->photo }});
                    background-size: cover;
  background-position: center;"></div>
              @endif
          </a>
          <div class="post-footer">
              <span style="cursor:default; " class="comment-label">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-message-circle-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                      <path d="M3 20l1.3 -3.9a9 8 0 1 1 3.4 2.9l-4.7 1"></path>
                  </svg>
                  <span>{{$post->comments()->count()}}</span>
              </span>
              
            <div class="reaction-holder">
                <?php
                    $reaction = $post->reactions()
                        ->where('user_id', auth()->id())
                        ->first();
            
                    if ($reaction) {
                        $type = $reaction->type;
                    } else {
                        $type = null;
                    }
                ?>
            
            
            
                    {!!Form::open(['route' => ['addReaction', $post->id], 'method' => 'post','enctype' => 'multipart/form-data','class'=>'form-horizontal','id'=>'add_reaction_form']) !!}
                    {!!Form::token()!!}
                <span class="reaction-label {{ $type == 'Like' ? 'user-reaction' : '' }}">
                        <button class="btn btn-outline-secondary reaction-label" type='submit' name='reaction_type' value='Like'>👍🏻</button>
                    <span class=reaction-count>{{$post->reactions()->where('type','Like')->count()}}</span>
                </span>
                    {!!Form::close()!!}                    
                    {!!Form::open(['route' => ['addReaction', $post->id], 'method' => 'post','enctype' => 'multipart/form-data','class'=>'form-horizontal','id'=>'add_reaction_form']) !!}
                    {!!Form::token()!!}
                <span class="reaction-label {{ $type == 'Dislike' ? 'user-reaction' : '' }}">
                        <button class="btn btn-outline-secondary reaction-label" type='submit' name='reaction_type' value='Dislike'>👎🏻</button>
                    {!!Form::close()!!}
                    <span class=reaction-count>{{$post->reactions()->where('type','Dislike')->count()}}</span>
                </span>
                    {!!Form::open(['route' => ['addReaction', $post->id], 'method' => 'post','enctype' => 'multipart/form-data','class'=>'form-horizontal','id'=>'add_reaction_form']) !!}
                    {!!Form::token()!!}
                <span class="reaction-label {{ $type == 'Sad' ? 'user-reaction' : '' }}">
                        <button class="btn btn-outline-secondary reaction-label" type='submit' name='reaction_type' value='Sad'>😿</button>
                    {!!Form::close()!!}
                    <span class=reaction-count>{{$post->reactions()->where('type','Sad')->count()}}</span>
                </span>
                    {!!Form::open(['route' => ['addReaction', $post->id], 'method' => 'post','enctype' => 'multipart/form-data','class'=>'form-horizontal','id'=>'add_reaction_form']) !!}
                    {!!Form::token()!!}
                <span class="reaction-label {{ $type == 'Angry' ? 'user-reaction' : '' }}">
                        <button class="btn btn-outline-secondary reaction-label" type='submit' name='reaction_type' value='Angry'>😡</button>
                    {!!Form::close()!!}
                    <span class=reaction-count>{{$post->reactions()->where('type','Angry')->count()}}</span>
                </span>
                    {!!Form::open(['route' => ['addReaction', $post->id], 'method' => 'post','enctype' => 'multipart/form-data','class'=>'form-horizontal','id'=>'add_reaction_form']) !!}
                    {!!Form::token()!!}
                <span class="reaction-label {{ $type == 'Amazed' ? 'user-reaction' : '' }}">
                        <button class="btn btn-outline-secondary reaction-label" type='submit' name='reaction_type' value='Amazed'>😍</button>
                    {!!Form::close()!!}
                    <span class=reaction-count>{{$post->reactions()->where('type','Amazed')->count()}}</span>
                </span>
            </div>   
          </div>
      </div>

      