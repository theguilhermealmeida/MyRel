
<div class="relationship">
    <div class="relationship-header">
    <img src={{ $relationship->photo }} >
    <h4 style="color: Orange;cursor:pointer; margin-left:10px;"><a href="/user/{{ $relationship->id }}">{{$relationship->name}}</a></h4>
    </div>

    <div class="relationship-add-info">{{$relationship->pivot->type}}<span  class="relationship-sub-add-info">{{$relationship->pivot->state}}</span></div>


</div>

<hr>