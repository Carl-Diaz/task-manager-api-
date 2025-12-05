<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'due_date',
        'is_completed',
        'priority'
    ];

    protected $casts = [
        'due_date' => 'date',
        'is_completed' => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Scope para tareas pendientes
    public function scopePending($query)
    {
        return $query->where('is_completed', false);
    }

    // Scope para tareas completadas
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    // Método para marcar como completada
    public function complete()
    {
        $this->update(['is_completed' => true]);
        return $this;
    }

    // Método para marcar como pendiente
    public function uncomplete()
    {
        $this->update(['is_completed' => false]);
        return $this;
    }

    // Verificar si está vencida
    public function isOverdue()
    {
        if (!$this->due_date || $this->is_completed) {
            return false;
        }
        return $this->due_date->isPast();
    }
}