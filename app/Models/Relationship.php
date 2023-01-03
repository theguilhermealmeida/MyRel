<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relationship extends Model
{
    public $timestamps  = false;

    protected $fillable = [
        'sender_id', 'recipient_id', 'state', 'type'
    ];

    public function sender()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function recipient()
    {
        return $this->belongsTo('App\Models\User', 'related_id');
    }

    public function getRelationship($sender_id, $recipient_id) 
    {
        $relationship = Relationship::where('sender_id', $sender_id)->where('recipient_id', $recipient_id)->first();
        // if ($relationship == null) {
        //     $relationship = Relationship::where('sender_id', $recipient_id)->where('recipient_id', $sender_id)->first();
        // }
        return $relationship;
    }

}
