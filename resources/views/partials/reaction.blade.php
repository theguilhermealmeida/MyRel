<div class="reaction" style="color: Red">
<h4 onclick="location.href='/user/{{ $reaction->user()->get()[0]->id }}';" style="color: Red;cursor:pointer">{{ $reaction->user()->get()[0]->name }}<img src={{ $reaction->user()->get()[0]->photo }} ></h2>
<div style="color: Turquoise">{{ $reaction->type }}</div>
</div>