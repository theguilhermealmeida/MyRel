<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commentreaction extends Model
{
    // Don't add create and update timestamps in database.
  public $timestamps  = false;

    /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'user_id', 'comment_id', 'type'
  ];

  /**
   * The user this commentreaction belongs to
   */
  public function user() {
    return $this->belongsTo('App\Models\User');
  }

    /**
   * The comment this reaction belongs to
   */
  public function comment() {
    return $this->belongsTo('App\Models\Comment');
  }
  
}