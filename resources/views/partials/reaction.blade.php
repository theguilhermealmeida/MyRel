
<div class="relationship">
    <div class="relationship-header">
    <img src={{ $reaction->user()->get()[0]->photo }} >
    <h4 style="color: Orange;cursor:pointer; margin-left:10px;"><a href="/user/{{ $reaction->user()->get()[0]->id }}">{{ $reaction->user()->get()[0]->name }}</a></h4>
    </div>

    <div class="relationship-add-info">{{ $reaction->type }}</div>


</div>

<hr>