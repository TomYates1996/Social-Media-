<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
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
        $posts = Post::all();  
        return Inertia::render('Explore', [
            'posts' => $posts,  
        ]);
    }

    public function getProfilePosts()
    {
        $posts = Post::where('username', $user->username)->get();
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
        $posts = Post::whereIn('user_id', [$followedUserIds, $authUser->id] )->orderBy('created_at', 'desc')->get();

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

}
