<?php

namespace App\Models\Notifications;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Commentreactionnotification extends Model
{
    // Don't add create and update timestamps in database.
  public $timestamps  = false;

  protected $fillable = [
    'read'
]; 

  /**
   * The user this Commentreactionnotification belongs to
   */
  public function user() {
    return $this->belongsTo('App\Models\User');
  }

    /**
   * The commentreaction this Commentreactionnotification belongs to
   */
  public function commentreaction() {
    return $this->belongsTo('App\Models\Commentreaction');
  }

  /**
   * 
   */
  
}
