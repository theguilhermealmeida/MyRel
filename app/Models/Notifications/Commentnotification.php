<?php

namespace App\Models\Notifications;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Commentnotification extends Model
{
    // Don't add create and update timestamps in database.
  public $timestamps  = false;

  
  protected $fillable = [
    'read'
];

  /**
   * The user this Commentnotification belongs to
   */
  public function user() {
    return $this->belongsTo('App\Models\User');
  }

    /**
   * The comment this Commentnotification belongs to
   */
  public function comment() {
    return $this->belongsTo('App\Models\Comment');
  }

  /**
   * 
   */
  
}
