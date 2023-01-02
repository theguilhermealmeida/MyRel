<?php

namespace App\Models\Notifications;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Replyreactionnotification extends Model
{
    // Don't add create and update timestamps in database.
  public $timestamps  = false;

  protected $fillable = [
    'read'
]; 

  /**
   * The user this Replyreactionnotification belongs to
   */
  public function user() {
    return $this->belongsTo('App\Models\User');
  }

    /**
   * The Replyreaction this Replyreactionnotification belongs to
   */
  public function replyreaction() {
    return $this->belongsTo('App\Models\Replyreaction');
  }
}
