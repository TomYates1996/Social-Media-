<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
            'postTitle' => 'required|string|max:255',
            'postBody' => 'nullable|string', 
            'location' => 'nullable|string|max:255'
        ]);

        try {
            if ($request->file('image')) {
                $imagePath = $request->file('image')->store('posts_images', 'public');
                $imageUrl = asset('storage/' . $imagePath);
            } else {
                $imageUrl = null;
            }
            

            $user = Auth::user();
            $data = $request->all();
            $data['title'] = $request->postTitle;
            $data['body'] = '';
            $data['image'] = $imageUrl;
            $data['user_id'] = $user['id'];
            $data['username'] = $user['username'];
            $data['location'] = $request->location ?? null;
            $post = Post::create($data);

            return $this->index();;
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Image upload failed: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function getAllPosts()
    {
        $posts = Post::with(['comments.user', 'comments.likes'])->orderBy('created_at', 'desc')->get();

        return Inertia::render('Explore', [
            'posts' => $posts,  
        ]);
    }

    public function getProfilePosts()
    {
        $posts = Post::where('username', $user->username)->with(['comments.user', 'comments.likes'])->get();
        return Inertia::render('Profile/Show', [
            'user' => $user,
            'posts' => $posts,
        ]);
    }

    public function loadCreate()
    {
        return Inertia::render('CreatePost', [
        ]);
    }

    public function index()
    {
        $authUser = auth()->user();
        $followedUserIds = $authUser->following()->pluck('followed_id');
        $posts = Post::whereIn('user_id', $followedUserIds->merge([$authUser->id]))->with(['comments.user', 'comments.likes'])->orderBy('created_at', 'desc')->get();

        return Inertia::render('Dashboard', [
            'posts' => $posts,  
        ]);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $authUser = auth()->user();
        if ($authUser->username == $post->username) {
            $post->delete(); 
            return response()->json(['message' => 'Post deleted successfully.']);
        } else {
            return;
        }
    }

    public function toggleLike($postId)
    {
        $post = Post::findOrFail($postId);
        $user = Auth::user();

        if ($user->likedPosts->contains($post)) {
            $user->likedPosts()->detach($post);
        } else {
            $user->likedPosts()->attach($post);
        }

        return response()->json([
            'likes_count' => $post->likers()->count(),
            'is_liked' => $user->likedPosts->contains($post),
        ]);
    }

    public function getLikes($postId) 
    {
        $post = Post::findOrFail($postId);
        $user = Auth::user();

        return response()->json([
            'is_liked' => $user->likedPosts->contains($post),
            'likes_count' => $post->likers()->count(),
        ]);
    }

    public function storeComment(Request $request, $postId)
    {
        $request->validate([
            'comment' => 'required|string|max:255',
        ]);

        $post = Post::findOrFail($postId);
        $user = auth()->user();

        $comment = $post->comments()->create([
            'user_id' => $user->id,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'message' => 'Comment added successfully',
            'comment' => $comment
        ], 201);
    }

    public function toggleLikeComment($commentId)
    {
        $user = auth()->user();
        $comment = Comment::findOrFail($commentId);

        if ($user->likedComments()->where('comment_id', $comment->id)->exists()) {
            $user->likedComments()->detach($comment);
            return response()->json(['message' => 'Unliked the comment']);
        } else {
            $user->likedComments()->attach($comment);
            return response()->json(['message' => 'Liked the comment']);
        }
    }

}
