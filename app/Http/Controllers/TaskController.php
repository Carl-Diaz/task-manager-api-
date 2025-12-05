<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // Listar tareas de un proyecto
    public function index($projectId)
    {
        try {
            $project = Auth::user()->projects()->find($projectId);

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Proyecto no encontrado'
                ], 404);
            }

            $tasks = $project->tasks()
                ->orderBy('is_completed')
                ->orderBy('due_date')
                ->orderBy('priority', 'desc')
                ->get();

            $stats = [
                'total' => $tasks->count(),
                'completed' => $tasks->where('is_completed', true)->count(),
                'pending' => $tasks->where('is_completed', false)->count(),
                'overdue' => $tasks->where('is_completed', false)
                    ->filter(function($task) {
                        return $task->isOverdue();
                    })->count(),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Tareas obtenidas exitosamente',
                'data' => $tasks,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener tareas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Crear nueva tarea
    public function store(Request $request, $projectId)
    {
        try {
            $project = Auth::user()->projects()->find($projectId);

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Proyecto no encontrado'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'due_date' => 'nullable|date|after_or_equal:today',
                'priority' => 'nullable|integer|between:1,5',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $task = Task::create([
                'project_id' => $project->id,
                'title' => $request->title,
                'description' => $request->description,
                'due_date' => $request->due_date,
                'priority' => $request->priority ?? 1,
                'is_completed' => false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tarea creada exitosamente',
                'data' => $task
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear tarea',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Mostrar tarea específica
    public function show($projectId, $id)
    {
        try {
            $project = Auth::user()->projects()->find($projectId);

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Proyecto no encontrado'
                ], 404);
            }

            $task = $project->tasks()->find($id);

            if (!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tarea no encontrada'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Tarea obtenida exitosamente',
                'data' => $task
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener tarea',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Actualizar tarea
    public function update(Request $request, $projectId, $id)
    {
        try {
            $project = Auth::user()->projects()->find($projectId);

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Proyecto no encontrado'
                ], 404);
            }

            $task = $project->tasks()->find($id);

            if (!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tarea no encontrada'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'due_date' => 'nullable|date',
                'priority' => 'nullable|integer|between:1,5',
                'is_completed' => 'sometimes|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $task->update($request->only(['title', 'description', 'due_date', 'priority', 'is_completed']));

            return response()->json([
                'success' => true,
                'message' => 'Tarea actualizada exitosamente',
                'data' => $task
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar tarea',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Eliminar tarea
    public function destroy($projectId, $id)
    {
        try {
            $project = Auth::user()->projects()->find($projectId);

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Proyecto no encontrado'
                ], 404);
            }

            $task = $project->tasks()->find($id);

            if (!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tarea no encontrada'
                ], 404);
            }

            $task->delete();

            return response()->json([
                'success' => true,
                'message' => 'Tarea eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar tarea',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Marcar tarea como completada
    public function complete($projectId, $id)
    {
        try {
            $project = Auth::user()->projects()->find($projectId);

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Proyecto no encontrado'
                ], 404);
            }

            $task = $project->tasks()->find($id);

            if (!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tarea no encontrada'
                ], 404);
            }

            $task->complete();

            return response()->json([
                'success' => true,
                'message' => 'Tarea marcada como completada',
                'data' => $task
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al completar tarea',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Marcar tarea como pendiente
    public function uncomplete($projectId, $id)
    {
        try {
            $project = Auth::user()->projects()->find($projectId);

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Proyecto no encontrado'
                ], 404);
            }

            $task = $project->tasks()->find($id);

            if (!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tarea no encontrada'
                ], 404);
            }

            $task->uncomplete();

            return response()->json([
                'success' => true,
                'message' => 'Tarea marcada como pendiente',
                'data' => $task
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al marcar tarea como pendiente',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}