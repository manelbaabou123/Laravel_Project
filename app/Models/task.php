<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Task extends Model
{
    use HasFactory;
    
    protected $table = 'tasks';
    protected $fillable = ['name', 'description','project_id', 'user_id'];

    public function project() : BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
