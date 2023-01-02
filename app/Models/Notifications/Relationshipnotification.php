<?php

namespace App\Models\Notifications;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Relationshipnotification extends Model
{
    // Don't add create and update timestamps in database.
  public $timestamps  = false;

  protected $fillable = [
    'read'
];

  /**
   * The user this Relationshipnotification belongs to
   */
  public function user() {
    return $this->belongsTo('App\Models\User');
  }

    /**
   * The relationship this Relationshipnotificatoin belongs to
   */
  public function post() {
    return $this->belongsTo('App\Models\Relationship');
  }

  /**
   * 
   */
  
}
