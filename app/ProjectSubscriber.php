<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectSubscriber extends Model
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
     * A subscriber is belongs to a project
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
