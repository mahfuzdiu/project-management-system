<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentCreationRequest;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Response;

class CommentController extends Controller
{
    /**
     * pulls list of comments
     * @param $taskId
     * @return mixed
     */
    public function getTaskComments($taskId)
    {
        return Comment::where('task_id', $taskId)->get();
    }

    public function createTaskComment(CommentCreationRequest $request, $taskId)
    {
        $task = Task::find($taskId);
        if ($task) {
            $validated = $request->validated();
            $validated['task_id'] = $taskId;
            $validated['user_id'] = auth()->user()->id;
            return Comment::create($validated);
        }
        return response()->json(['message' => __('messages.task_not_found')], Response::HTTP_NOT_FOUND);
    }
}
