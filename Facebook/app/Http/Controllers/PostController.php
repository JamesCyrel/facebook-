<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
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
        $posts = Post::with(['user', 'comments.user', 'reacts'])
            ->latest()
            ->paginate(10);
            
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048', // 2MB max
        ]);
        
        $data = [
            'user_id' => Auth::id(),
            'content' => $request->content,
        ];
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('post-images', 'public');
            $data['image'] = $path;
        }
        
        Post::create($data);
        
        return redirect()->route('posts.index')
            ->with('success', 'Post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load(['user', 'comments.user', 'reacts.user']);
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        // Check if user is authorized to edit this post
        if ($post->user_id !== Auth::id()) {
            return redirect()->route('posts.index')
                ->with('error', 'You are not authorized to edit this post.');
        }
        
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // Check if user is authorized to update this post
        if ($post->user_id !== Auth::id()) {
            return redirect()->route('posts.index')
                ->with('error', 'You are not authorized to update this post.');
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
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            
            $path = $request->file('image')->store('post-images', 'public');
            $data['image'] = $path;
        }
        
        $post->update($data);
        
        return redirect()->route('posts.show', $post)
            ->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Check if user is authorized to delete this post
        if ($post->user_id !== Auth::id()) {
            return redirect()->route('posts.index')
                ->with('error', 'You are not authorized to delete this post.');
        }
        
        // Delete image if exists
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }
        
        $post->delete();
        
        return redirect()->route('posts.index')
            ->with('success', 'Post deleted successfully.');
    }
}
