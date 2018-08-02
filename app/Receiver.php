<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receiver extends Model
{
    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        'chat_id', 'name', 'username', 'token', 'status'
    ];

    /**
     * A receiver is belongs to a server
     */
    public function server()
    {
        return $this->belongsTo(Server::class);
    }
}
