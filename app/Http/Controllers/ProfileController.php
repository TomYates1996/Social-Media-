<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;


class ProfileController extends Controller
{
    public function show($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $posts = Post::where('username', $username)->orderBy('created_at', 'desc')->get();
        $isFollowing = auth()->check() ? auth()->user()->isFollowing($user) : false;
        $followers = $user->followers; 
        $followerCount = $followers->count();
        $following = $user->following;
        $followingCount = $following->count();

        $userActual = Auth::user();

        return Inertia::render('Profile', [
            'user' => $user,
            'followers' => $followers,
            'followerCount' => $followerCount,
            'posts' => $posts,
            'isFollowing' => $isFollowing,
            'following' => $following,
            'followingCount' => $followingCount,
        ]);
    }

    public function follow($username)
    {
        $userToFollow = User::where('username', $username)->firstOrFail();
        $authUser = auth()->user();

        if ($authUser->id === $userToFollow->id) {
            return response()->json(['message' => 'You cannot follow yourself.'], 400);
        }

        if ($authUser->isFollowing($userToFollow)) {
            return response()->json(['message' => 'You are already following this user.'], 400);
        }

        $authUser->following()->attach($userToFollow);

        return response()->json(['message' => 'You are now following this user.']);
    }

    public function unfollow($username)
    {
        $userToUnfollow = User::where('username', $username)->firstOrFail();
        $authUser = auth()->user();

        if (!$authUser->isFollowing($userToUnfollow)) {
            return response()->json(['message' => 'You are not following this user.'], 400);
        }

        $authUser->following()->detach($userToUnfollow);

        return response()->json(['message' => 'You have unfollowed this user.']);
    }

    // Settings page 
    public function settings()
    {
        $user = Auth::user();

        return Inertia::render('Settings', [
            'user' => $user,
        ]);
    }
}
