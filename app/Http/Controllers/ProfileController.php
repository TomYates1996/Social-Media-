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
        $posts = Post::where('username', $username)->get();
        $isFollowing = auth()->check() ? auth()->user()->isFollowing($user) : false;

        $userActual = Auth::user();

        return Inertia::render('Profile', [
            'user' => $user,
            'posts' => $posts,
            'isFollowing' => $isFollowing,
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
}
