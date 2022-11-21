<div class="post card" data-id="{{ $post->id }}">
          <div class="post-header">
              <img src={{ $post->user()->get()[0]->photo }} class="post-profile-pic">
              <div class="post-header-info">
                  <h3><a href="/user/{{ $post->user()->get()[0]->id }}">{{ $post->user()->get()[0]->getName() }}</a></h3>
                  <p>{{ $post->date }}</p>
              </div>
          </div>
          <a class="post-body" href="/posts/{{ $post->id }}">
              <p>{{ $post->text }}</p>
              <!-- <img src="https://www.algavalor.pt/wp-content/uploads/2020/04/FEUP-Autoria-Global-Imagens.jpg" class="post-image"> -->
          </a>
          <div class="post-footer">
              <span class="comment-label">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-message-circle-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                      <path d="M3 20l1.3 -3.9a9 8 0 1 1 3.4 2.9l-4.7 1"></path>
                  </svg>
                  <span>{{$post->comments()->count()}}</span>
              </span>
              <div class="reaction-holder">
                  <span class="reaction-label">
                      <span>👍🏻</span>
                      <span>{{$post->reactions()->where('type','Like')->count()}}</span>
                  </span>
                  <span class="reaction-label">
                      <span>👎🏻</span>
                      <span>{{$post->reactions()->where('type','Dislike')->count()}}</span>
                  </span>
                  <span class="reaction-label">
                      <span>😿</span>
                      <span>{{$post->reactions()->where('type','Sad')->count()}}</span>
                  </span>
                  <span class="reaction-label">
                      <span>😡</span>
                      <span>{{$post->reactions()->where('type','Angry')->count()}}</span>
                  </span>
                  <span class="reaction-label">
                      <span>😍</span>
                      <span>{{$post->reactions()->where('type','Amazed')->count()}}</span>
                  </span>
              </div>
          </div>
      </div>

