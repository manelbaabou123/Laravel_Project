<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class task extends Model
{
    use HasFactory;
    
    protected $table = 'tasks';
    protected $fillable = ['project_id','name', 'description'];

    public function project() : BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
