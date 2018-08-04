<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
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
     * A receiver is belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
