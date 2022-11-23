<h4 onclick="location.href='/user/{{ $user->id }}';" style="cursor:pointer;">
                {{ $user->name }}</a><img src={{ $user->photo }}></h4>
<p>{{$user->description}}</p>