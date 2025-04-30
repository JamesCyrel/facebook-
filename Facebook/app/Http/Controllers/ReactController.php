<?php

namespace App\Http\Controllers;

use App\Models\React;
use App\Models\Post;
use Illuminate\Http\Request;

class ReactController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(React $react)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(React $react)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, React $react)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(React $react)
    {
        //
    }

    /**
     * Toggle like/unlike a post.
     */
    public function toggle(Post $post)
    {
        // Check if the post has already been reacted to by the current user
        $react = React::where('user_id', auth()->id())
                ->where('post_id', $post->id)
                ->first();
        
        if ($react) {
            // Unlike: Delete the react if it exists
            $react->delete();
            return redirect()->back()->with('success', 'Post unliked successfully.');
        } else {
            // Like: Create new react
            React::create([
                'user_id' => auth()->id(),
                'post_id' => $post->id,
            ]);
            return redirect()->back()->with('success', 'Post liked successfully.');
        }
    }
}
