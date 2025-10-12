<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleEnum;
use App\Http\Requests\CommentCreationRequest;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CommentController extends Controller implements HasMiddleware
{
    /**
     * @return Middleware[]
     */
    public static function middleware(): array
    {
        return [
            new Middleware('roles:' . UserRoleEnum::MANAGER->value, only: ['destroy']),
        ];
    }

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

    /**
     * @param $taskId
     * @param $commentId
     * @return JsonResponse
     */
    public function destroy($taskId, $commentId)
    {
        $comment = Comment::where('task_id', $taskId)->where('id', $commentId)->firstOrFail();
        $comment->delete();
        return response()->json(['message' => __('messages.comment_deleted')], Response::HTTP_OK);
    }
}
