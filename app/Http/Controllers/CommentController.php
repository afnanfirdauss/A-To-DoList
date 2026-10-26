<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Notification;
use App\Models\Task;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use AuthorizesRequests;

    /**
     * Store a new comment on a task.
     */
    public function store(Request $request, Task $task): RedirectResponse
    {
        $this->authorize('view', $task);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ], [
            'body.required' => 'Isi komentar tidak boleh kosong.',
        ]);

        $comment = $task->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
        ]);

        // Notify creator & assignee if they are not the commenter
        $recipients = array_unique(array_filter([
            $task->created_by,
            $task->assigned_to,
        ]));

        foreach ($recipients as $recipientId) {
            if ($recipientId !== $request->user()->id) {
                Notification::create([
                    'user_id' => $recipientId,
                    'task_id' => $task->id,
                    'type' => 'task_comment',
                    'message' => "{$request->user()->name} menambahkan komentar pada tugas '{$task->title}'.",
                ]);
            }
        }

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(Request $request, Comment $comment): RedirectResponse
    {
        $user = $request->user();

        // User can delete their own comment, or if they are the task creator or project owner
        $isAuthor = $comment->user_id === $user->id;
        $isTaskCreator = $comment->task->created_by === $user->id;
        $isProjectOwner = $comment->task->project && $comment->task->project->user_id === $user->id;

        if (! $isAuthor && ! $isTaskCreator && ! $isProjectOwner) {
            abort(403, 'Anda tidak memiliki hak untuk menghapus komentar ini.');
        }

        $comment->delete();

        return back()->with('success', 'Komentar berhasil dihapus.');
    }
}
