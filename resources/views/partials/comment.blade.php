


<div class="post card" data-id="{{ $comment->id }}">
          <div class="post-header">
              <img src={{ $comment->user()->get()[0]->photo }} class="post-profile-pic">
              <div class="post-header-info">
                  <h3><a href="/user/{{ $comment->user()->get()[0]->id }}">{{ $comment->user()->get()[0]->name }}</a></h3>
                  <p>{{ $comment->date }}</p>
              </div>
              @can('delete', $comment)
              {!!Form::open(['url' => 'api/comments/' . $comment->id, 'method' => 'delete'])!!}
                <button type="submit" class="mx-auto btn btn-danger btn-sm">Delete Comment</button>
              {!!Form::close()!!}
              @endcan
          </div>
          <a class="post-body">
              <p>{{ $comment->text }}</p>
          </a>

          <div class="post-footer">
              <span class="comment-label">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-message-circle-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                      <path d="M3 20l1.3 -3.9a9 8 0 1 1 3.4 2.9l-4.7 1"></path>
                  </svg>
                  <span>{{$comment->replies()->count()}}</span>
              </span>
              <div class="reaction-holder">
                  <span class="reaction-label">
                      <span>👍🏻</span>
                      <span>{{$comment->reactions()->where('type','Like')->count()}}</span>
                  </span>
                  <span class="reaction-label">
                      <span>👎🏻</span>
                      <span>{{$comment->reactions()->where('type','Dislike')->count()}}</span>
                  </span>
                  <span class="reaction-label">
                      <span>😿</span>
                      <span>{{$comment->reactions()->where('type','Sad')->count()}}</span>
                  </span>
                  <span class="reaction-label">
                      <span>😡</span>
                      <span>{{$comment->reactions()->where('type','Angry')->count()}}</span>
                  </span>
                  <span class="reaction-label">
                      <span>😍</span>
                      <span>{{$comment->reactions()->where('type','Amazed')->count()}}</span>
                  </span>
              </div>
          </div>

        <section id="replies" style="margin-left:25px;">
                @each('partials.reply', $comment->replies()->get(), 'reply')
        </section>

      </div>
