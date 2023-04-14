<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Task extends Model
{
    use HasFactory;
    
    protected $table = 'tasks';
    protected $fillable = ['name', 'description','project_id','creator_id'];

    public function project() : BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function creator() : BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
