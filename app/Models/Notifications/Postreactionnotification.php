<?php

namespace App\Models\Notifications;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Postreactionnotification extends Model
{
    // Don't add create and update timestamps in database.
  public $timestamps  = false;
  
  protected $fillable = [
    'read'
];
  /**
   * The user this Postreactionnotification belongs to
   */
  public function user() {
    return $this->belongsTo('App\Models\User');
  }

    /**
   * The postreaction this Postreactionnotification belongs to
   */
  public function Postreaction() {
    return $this->belongsTo('App\Models\Postreaction');
  }
}
