<h4 onclick="location.href='/user/{{ $user->id }}';" style="cursor:pointer;">
                {{ $user->name }}</a><img src={{ $user->photo }} alt={{$user->id}}></h4>
<p>{{$user->description}}</p>