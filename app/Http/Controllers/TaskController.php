<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskCreationRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Models\Project;
use App\Models\Task;
use App\Services\TaskAssignmentService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TaskController extends Controller implements HasMiddleware
{
    /**
     * @return Middleware[]
     */
    public static function middleware(): array
    {
        return [
            new Middleware('manager', only: ['createProjectTask', 'destroy', 'update'])
        ];
    }

    /**
     * @param Task $task
     * @return Task
     */
    public function show(Task $task)
    {
        return $task;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskUpdateRequest $request, Task $task, TaskAssignmentService $taskAssignmentService)
    {
        $task->update($request->validated());
        $taskAssignmentService->sendNotificaionToAssingedUser($task);
        return $task;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        // Check if the task has related comments
        if ($task->comments()->exists()) {
            return response()->json([
                'message' => __('messages.task_cannot_be_deleted')
            ], Response::HTTP_BAD_REQUEST);
        }
        $task->delete();
        return response()->json([
            'message' => __('messages.task_deleted')
        ], 200);
    }

    /**
     * @param $projectId
     * @return mixed
     */
    public function getProjectTasks($projectId)
    {
        return Task::where('project_id', $projectId)->get();
    }

    /**
     * @param TaskCreationRequest $request
     * @return mixed
     */
    public function createProjectTask(TaskCreationRequest $request, $projectId)
    {
        $project = Project::find($projectId);
        if($project){
            $validated = $request->validated();
            $validated['project_id'] = $projectId;
            return Task::create($validated);
        }
        return response()->json(['message' => __('messages.project_not_found')], Response::HTTP_NOT_FOUND);
    }
}
