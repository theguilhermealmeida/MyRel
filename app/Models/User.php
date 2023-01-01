<?php

namespace App\Models;

use App\Models\Notifications\Relationshipnotification;

use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword as ResetPasswordContract;
use Illuminate\Auth\Passwords\CanResetPassword;


class User extends Authenticatable
{
    use Notifiable;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','ban'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function getName() {
      if ($this->ban) {
      return "unkown account";
      }
      else return $this->name;
    }

    /**
     * The cards this user owns.
     */
     public function cards() {
      return $this->hasMany('App\Models\Card');
    }

    /**
     * The posts this user owns.
     */
    public function posts() {
        return $this->hasMany('App\Models\Post');
      }

    /**
     * The comments this user owns.
     */
    public function comments() {
        return $this->hasMany('App\Models\Comment');
      }

    /**
     * The replies this user owns.
     */
    public function replies() {
        return $this->hasMany('App\Models\Reply');
      }

    /**
     * The relationships this user has.
     */
    public function relationships() {
      return $this->belongsToMany(User::class, 'relationships', 'user_id', 'related_id')->withPivot('id','type', 'state');
    }

    public function relationships2() {
      return $this->belongsToMany(User::class, 'relationships', 'related_id', 'user_id')->withPivot('id','type', 'state');
    }

    public function getAllRelationships($id) {
      $user = User::find($id);
      
      $relationships = $user->relationships()->orderBy('id')->get();
      $relationships = $relationships->merge($user->relationships()->orderBy('id')->get());

      return $relationships;
    }

    public function getRelationship($sender_id, $receiver_id) {
      $relationships = $this->relationships()->where('user_id', $sender_id)->where('related_id', $receiver_id)->orderBy('id')->get();
      $relationships = $relationships->merge($this->relationships2()->where('user_id', $sender_id)->where('related_id', $receiver_id)->orderBy('id')->get());

      if ($relationships == null) {
        $relationships = $this->relationships()->where('user_id', $receiver_id)->where('related_id', $sender_id)->orderBy('id')->get();
        $relationships = $relationships->merge($this->relationships2()->where('user_id', $receiver_id)->where('related_id', $sender_id)->orderBy('id')->get()); 
      }
      return $relationships->first();

      // return $relationships->where('user_id', $sender_id)->where('related_id', $receiver_id)->first;
    }


    /**
     * The postreactions this user has.
     */
    public function postreactions() {
      return $this->hasMany('App\Models\Postreaction');
    }

    /**
     * The commentreactions this user has.
     */
    public function commentreactions() {
      return $this->hasMany('App\Models\Commentreaction');
    }

    /**
     * The replyreactions this user has.
     */
    public function replyreactions() {
      return $this->hasMany('App\Models\Replyreaction');
    }


    public function scopeSearch($query, $search)
    {
      if(!$search){
        return $query;
      }
      return $query->whereRaw('tsvectors @@ plainto_tsquery(\'english\',?)',[$search])
            ->orWhere('name', 'LIKE', '%' . $search . '%')->orWhere('description', 'LIKE', '%' . $search . '%')
          ->orderByRaw('ts_rank(tsvectors,plainto_tsquery(\'english\',?)) DESC',[$search]);
    }

    /**
     * The relationship notifications this user has.
     */
    public function unreadrelationshipnotifications() 
    {
      if (!Auth::check() || Auth::user()->id != $this->id)
      {
        abort(403);
      }

      return $this->hasMany('App\Models\Notifications\Relationshipnotification', 'receiver_id')->where('read', 0);
    }
}