
<div class="relationship">
    <div class="relationship-header">
    <img src={{ $relationship->photo }} alt=id={{ $relationship->id }} >
    <h4 style="color: Orange;cursor:pointer; margin-left:10px;"><a href="/user/{{ $relationship->id }}">{{$relationship->name}}</a></h4>
    </div>

    <div class="relationship-add-info">{{$relationship->pivot->type}}<span  class="relationship-sub-add-info">{{$relationship->pivot->state}}</span></div>

    <div class="mt-2">
    @if ($relationship->pivot->state == 'pending')
        <div class="btn-group" role="group" aria-label="Basic example">

            {!! Form::open(['url' => 'api/relationships/' . $relationship->pivot->id, 'method' => 'post']) !!}
                    <button type="submit" class="btn btn-primary btn-sm mr-2">Accept Request</button>
            {!! Form::close() !!}
            {!! Form::open(['url' => 'api/relationships/' . $relationship->pivot->id, 'method' => 'delete']) !!}
                    <button type="submit" class="btn btn-secondary btn-sm">Decline Request</button>
            {!! Form::close() !!}
        </div>
    @endif

    @if ($relationship->pivot->state == 'accepted')
            {!! Form::open(['url' => 'api/relationships/' . $relationship->pivot->id, 'method' => 'delete']) !!}
                    <button type="submit" class="btn btn-danger btn-sm">Remove Relationship</button>
            {!! Form::close() !!}
    @endif
    </div>


</div>

<hr>