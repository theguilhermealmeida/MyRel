


<div class="post card" data-id="{{ $comment->id }}">
          <div class="post-header">
              <img src={{ $comment->user()->get()[0]->photo }} class="post-profile-pic">
              <div class="post-header-info">
                  <h3><a href="/user/{{ $comment->user()->get()[0]->id }}">{{ $comment->user()->get()[0]->name }}</a></h3>
                  <p>{{ $comment->date }}</p>
              </div>
          </div>
          <a class="post-body">
              <p>{{ $comment->text }}</p>
          </a>

          <h3>Replies</h3>
          <section id="replies" style="margin-left:25px;">
  @each('partials.reply', $comment->replies()->get(), 'reply')
</section>

      </div>
