<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'is_archived'
    ];

    protected $casts = [
        'is_archived' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // Scope para proyectos activos
    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    // Scope para proyectos archivados
    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    // Método para archivar proyecto
    public function archive()
    {
        $this->update(['is_archived' => true]);
        return $this;
    }

    // Método para desarchivar proyecto
    public function unarchive()
    {
        $this->update(['is_archived' => false]);
        return $this;
    }
}