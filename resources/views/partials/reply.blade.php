


<div class="post reply" style="font-size: 13px;">
<div class="post-header">
              <img src={{ $reply->user()->get()[0]->photo }} class="post-profile-pic">
              <div class="post-header-info">
                  <h3><a href="/user//user/{{ $reply->user()->get()[0]->id }}">{{ $reply->user()->get()[0]->name }}</a></h3>
                  <p>{{ $reply->date }}</p>
              </div>
          </div>

          <a class="post-body">
              <p>{{ $reply->text }}</p>
          </a>

</div>

<hr style="margin:0;">


