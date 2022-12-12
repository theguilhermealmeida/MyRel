

<hr style="margin:1em;">
<div class="post reply" style="font-size: 13px;">
<div class="post-header">
              <img src={{ $reply->user()->get()[0]->photo }} class="post-profile-pic">
              <div class="post-header-info">
                  <h3><a href="/user//user/{{ $reply->user()->get()[0]->id }}">{{ $reply->user()->get()[0]->name }}</a></h3>
                  <p>{{ $reply->date }}</p>
              </div>
             @can('update', $reply)
                <button id="toggle_edit_reply" class="mx-auto btn btn-primary btn-sm">Edit Reply</button>
              @endcan
              @can('delete', $reply)
              {!!Form::open(['url' => 'api/replies/' . $reply->id, 'method' => 'delete'])!!}
                <button type="submit" class="mx-auto btn btn-danger btn-sm">Delete Reply</button>
              {!!Form::close()!!}
              @endcan
          </div>

          <a class="post-body">
              <p>{{ $reply->text }}</p>
          </a>

          <div class="post-footer">
              <div class="reaction-holder">
                  <span class="reaction-label">
                      <span>👍🏻</span>
                      <span>{{$reply->reactions()->where('type','Like')->count()}}</span>
                  </span>
                  <span class="reaction-label">
                      <span>👎🏻</span>
                      <span>{{$reply->reactions()->where('type','Dislike')->count()}}</span>
                  </span>
                  <span class="reaction-label">
                      <span>😿</span>
                      <span>{{$reply->reactions()->where('type','Sad')->count()}}</span>
                  </span>
                  <span class="reaction-label">
                      <span>😡</span>
                      <span>{{$reply->reactions()->where('type','Angry')->count()}}</span>
                  </span>
                  <span class="reaction-label">
                      <span>😍</span>
                      <span>{{$reply->reactions()->where('type','Amazed')->count()}}</span>
                  </span>
              </div>
          </div>

            @can('update', $reply)
            <div style="display:none" id="edit_reply" class="post card mb-3">
            {!!Form::open(['url' => 'api/replies/' . $reply->id, 'method' => 'post','class'=>'form-horizontal','id'=>'edit_reply_form']) !!}
            {!! Form::token() !!}
                <div class="form-group">
                    <div>
                    <textarea required name="text" onkeyup="countChars(this,document.getElementById('charNumReplyEdit'),280);" maxlength="280" id="edit_reply_text" class="form-control" rows="5">{{ $reply->text }}</textarea>
                    <p id="charNumReplyEdit">0 characters</p>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary float-right">Edit</button>
                </div>
            {!! Form::close() !!}
        </div>
        @endcan

</div>


