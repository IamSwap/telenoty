<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
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
     * A Project is belongs to an user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
    * A Project is belongs to many subscribers
    */
    public function subscribers()
    {
        return $this->hasMany(ProjectSubscriber::class);
    }
}
