<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{
    protected $fillable = ['name'];

    public function tasks()
    {
        return $this->hasMany('App\Models\Task', 'status_id');
    }
}
