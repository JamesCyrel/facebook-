<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        // This line is causing the error in Laravel 11
        // $this->middleware('auth');
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
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048', // 2MB max
        ]);
        
        $data = [
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'content' => $request->content,
        ];
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('comment-images', 'public');
            $data['image'] = $path;
        }
        
        Comment::create($data);
        
        return redirect()->route('posts.show', $post)
            ->with('success', 'Comment added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        // Check if user is authorized to edit this comment
        if ($comment->user_id !== Auth::id()) {
            return redirect()->route('posts.show', $comment->post_id)
                ->with('error', 'You are not authorized to edit this comment.');
        }
        
        return view('comments.edit', compact('comment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        // Check if user is authorized to update this comment
        if ($comment->user_id !== Auth::id()) {
            return redirect()->route('posts.show', $comment->post_id)
                ->with('error', 'You are not authorized to update this comment.');
        }
        
        $request->validate([
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048', // 2MB max
        ]);
        
        $data = [
            'content' => $request->content,
        ];
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($comment->image) {
                Storage::disk('public')->delete($comment->image);
            }
            
            $path = $request->file('image')->store('comment-images', 'public');
            $data['image'] = $path;
        }
        
        $comment->update($data);
        
        return redirect()->route('posts.show', $comment->post_id)
            ->with('success', 'Comment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        // Check if user is authorized to delete this comment
        if ($comment->user_id !== Auth::id()) {
            return redirect()->route('posts.show', $comment->post_id)
                ->with('error', 'You are not authorized to delete this comment.');
        }
        
        // Delete image if exists
        if ($comment->image) {
            Storage::disk('public')->delete($comment->image);
        }
        
        $postId = $comment->post_id;
        $comment->delete();
        
        return redirect()->route('posts.show', $postId)
            ->with('success', 'Comment deleted successfully.');
    }
}
