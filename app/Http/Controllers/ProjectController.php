<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // Listar proyectos del usuario autenticado
    public function index()
    {
        try {
            $projects = Auth::user()->projects()
                ->withCount(['tasks', 'tasks as completed_tasks_count' => function($query) {
                    $query->where('is_completed', true);
                }])
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Proyectos obtenidos exitosamente',
                'data' => $projects
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener proyectos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Crear nuevo proyecto
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $project = Project::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'description' => $request->description,
                'is_archived' => false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Proyecto creado exitosamente',
                'data' => $project
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear proyecto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Mostrar proyecto específico con sus tareas
    public function show($id)
    {
        try {
            $project = Auth::user()->projects()
                ->with(['tasks' => function($query) {
                    $query->orderBy('is_completed')
                          ->orderBy('due_date')
                          ->orderBy('priority', 'desc');
                }])
                ->find($id);

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Proyecto no encontrado'
                ], 404);
            }

            // Calcular estadísticas
            $stats = [
                'total_tasks' => $project->tasks->count(),
                'completed_tasks' => $project->tasks->where('is_completed', true)->count(),
                'pending_tasks' => $project->tasks->where('is_completed', false)->count(),
                'overdue_tasks' => $project->tasks->where('is_completed', false)
                    ->filter(function($task) {
                        return $task->isOverdue();
                    })->count(),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Proyecto obtenido exitosamente',
                'data' => $project,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener proyecto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Actualizar proyecto
    public function update(Request $request, $id)
    {
        try {
            $project = Auth::user()->projects()->find($id);

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Proyecto no encontrado'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'is_archived' => 'sometimes|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $project->update($request->only(['name', 'description', 'is_archived']));

            return response()->json([
                'success' => true,
                'message' => 'Proyecto actualizado exitosamente',
                'data' => $project
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar proyecto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Eliminar proyecto
    public function destroy($id)
    {
        try {
            $project = Auth::user()->projects()->find($id);

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Proyecto no encontrado'
                ], 404);
            }

            $project->delete();

            return response()->json([
                'success' => true,
                'message' => 'Proyecto eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar proyecto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Archivar proyecto
    public function archive($id)
    {
        try {
            $project = Auth::user()->projects()->find($id);

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Proyecto no encontrado'
                ], 404);
            }

            $project->archive();

            return response()->json([
                'success' => true,
                'message' => 'Proyecto archivado exitosamente',
                'data' => $project
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al archivar proyecto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Desarchivar proyecto
    public function unarchive($id)
    {
        try {
            $project = Auth::user()->projects()->find($id);

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Proyecto no encontrado'
                ], 404);
            }

            $project->unarchive();

            return response()->json([
                'success' => true,
                'message' => 'Proyecto desarchivado exitosamente',
                'data' => $project
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al desarchivar proyecto',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}