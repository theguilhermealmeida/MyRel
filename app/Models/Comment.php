<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // Don't add create and update timestamps in database.
  public $timestamps  = false;

  /**
   * The user this comment belongs to
   */
  public function user() {
    return $this->belongsTo('App\Models\User');
  }

    /**
   * The post this comment belongs to
   */
  public function post() {
    return $this->belongsTo('App\Models\Post');
  }

    /**
  * The replies this comment has.
  */
  public function replies() {
    return $this->hasMany('App\Models\Reply');
}

    /**
     * The commentreactions that belong to the comment.
     */
    public function reactions() {
      return $this->hasMany('App\Models\Commentreaction');
  }

  public function scopeSearch($query, $search)
  {
    if(!$search){
      return $query;
    }
    return $query->whereRaw('tsvectors @@ plainto_tsquery(\'english\',?)',[$search])
    ->orWhere('text', 'LIKE', '%' . $search . '%')
    ->orderByRaw('ts_rank(tsvectors,plainto_tsquery(\'english\',?)) DESC',[$search]);
  }

}
