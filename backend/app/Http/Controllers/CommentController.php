<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;

class CommentController extends Controller
{
    use ValidatesRequests;

    public function index($postId)
    {
        $comments = Comment::where('post_id', $postId)->get();
        return response()->json($comments);
    }

    public function store(Request $request, $post_id)
    {
        // Validate incoming request
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'content' => 'required|string',
        ]);

        // Create and save the new comment
        $comment = new Comment();
        $comment->post_id = $post_id;
        $comment->name = $request->input('name');
        $comment->email = $request->input('email');
        $comment->content = $request->input('content');
        $comment->save();

        return response()->json($comment, 201); // 201 Created
    }

    public function update(Request $request, $id)
    {
        // Validate incoming request
        $this->validate($request, [
            'content' => 'required|string',
        ]);

        // Find and update the comment
        $comment = Comment::findOrFail($id);
        $comment->update($request->all());
        return response()->json($comment, 200); // 200 OK
    }

    public function destroy($id)
    {
        // Find and delete the comment
        $comment = Comment::findOrFail($id);
        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }

    public function like($id)
    {
        // Find the comment and increase likes
        $comment = Comment::findOrFail($id);
        $comment->likes = ($comment->likes ?? 0) + 1;
        $comment->save();

        return response()->json(['message' => 'Comment liked successfully', 'comment' => $comment], 200);
    }

    public function dislike($id)
    {
        // Find the comment and increase dislikes
        $comment = Comment::findOrFail($id);
        $comment->dislikes = ($comment->dislikes ?? 0) + 1;
        $comment->save();

        return response()->json(['message' => 'Comment disliked successfully', 'comment' => $comment], 200);
    }
	
	public function deleteSelected(Request $request, $post_id)
{
		$commentIds = $request->input('comment_ids'); // Expecting an array of comment IDs

		// Validate that the IDs are an array
		if (!is_array($commentIds)) {
			return response()->json(['error' => 'Invalid input'], 400);
		}

		// Delete the comments
		Comment::whereIn('id', $commentIds)->delete();

    return response()->json(['message' => 'Selected comments deleted successfully']);
}
}
