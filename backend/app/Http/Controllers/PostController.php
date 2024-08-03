<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    // Public posts index
    public function publicIndex(Request $request)
    {
        //$query = Post::with('user');
		$query = Post::where('status', 'published')->with('user');

        // Filter by status if present in request
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by created_at if present in request
        if ($request->has('created_at')) {
            $query->whereDate('created_at', $request->input('created_at'));
        }

        //$posts = $query->get(); //yg lama bisa
		// Sort by created_at if sort option is present in request
		//
		if ($request->has('sort')) {
			$sortOption = $request->input('sort');
			if ($sortOption === 'latest') {
				$query->orderBy('updated_at', 'desc');
			} elseif ($sortOption === 'oldest') {
				$query->orderBy('updated_at', 'asc');
			}
		}
		//$query = Post::where('status', 'published');
		// Paginate the results
		$posts = $query->paginate(5);
        return response()->json($posts);
    }
    
    // Authenticated user's posts
    public function index(Request $request)
    {
		
        $query = Post::query();
        
        // Get the ID of the logged-in user
        $userId = Auth::id();

        // Filter by status if present in request
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by specific post ID if provided
        if ($request->has('post_id')) {
            $query->where('post_id', $request->input('post_id')); // Use 'post_id'
			\Log::info("index post with ID: $post_id");
        } else {
            // Default to showing posts of the currently authenticated user
            $query->where('user_id', $userId);
        }

        // Filter by created_at if present in request
        if ($request->has('created_at')) {
            $query->whereDate('created_at', $request->input('created_at'));
        }

        $posts = $query->get();
		
        return response()->json($posts);
    }

    public function show($post_id)
	{
		\Log::info("Fetching post with ID: $post_id");
		$post = Post::with('user')->find($post_id); // Make sure 'user' is defined in Post model

		if (!$post) {
			\Log::error("Post with ID $post_id not found.");
			return response()->json(['message' => 'Post not found'], 404);
		}

		return response()->json($post, 200); // 200 OK
	}


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|string|in:draft,published',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $post = Post::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'user_id' => Auth::id(), // Storing the logged-in user's id as foreign key
            'status' => $request->input('status', 'draft'),
        ]);

        return response()->json($post, 201); // 201 Created
    }

    public function update(Request $request, $post_id)
	{
		\Log::info("Updating post with ID: $post_id");
		$validator = Validator::make($request->all(), [
			'title' => 'required|string|max:255',
			'content' => 'required|string',
			'status' => 'required|string|in:draft,published',
		]);

		if ($validator->fails()) {
			return response()->json(['errors' => $validator->errors()], 422);
		}

		$post = Post::find($post_id);
		if (!$post) {
			\Log::error("Post with ID $post_id not found.");
			return response()->json(['message' => 'Post not found'], 404);
		}

		//$post->update($request->only(['title', 'content', 'status']));
		$post->update($request->all());
		$post->updated_at = now();
		$post->save();

		return response()->json($post, 200); // 200 OK
	}


    public function destroy($post_id)
    {
        $post = Post::where('post_id', $post_id)->firstOrFail();
        $post->delete();
        return response()->json(['message' => 'Post deleted successfully'], 200);
    }

    public function like($post_id)
    {
        $post = Post::where('post_id', $post_id)->firstOrFail();
        $post->likes = ($post->likes ?? 0) + 1;
        $post->save();

        return response()->json(['message' => 'Post liked successfully', 'post' => $post], 200);
    }

    public function dislike($post_id)
    {
        $post = Post::where('post_id', $post_id)->firstOrFail();
        $post->dislikes = ($post->dislikes ?? 0) + 1;
        $post->save();

        return response()->json(['message' => 'Post disliked successfully', 'post' => $post], 200);
    }
}
