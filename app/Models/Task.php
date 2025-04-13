<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    protected $fillable = [
        'name',
        'description',
        'status_id',
        'assigned_to_id'
    ];

    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'creator_by_id');
    }
}
