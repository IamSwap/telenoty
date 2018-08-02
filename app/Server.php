<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        'title', 'token', 'status'
    ];

    /**
     * A server is belongs to an user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A server can have many receiver
     */
    public function receivers()
    {
        return $this->hasMany(Receiver::class);
    }
}
