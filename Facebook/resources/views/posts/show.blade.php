<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Post Details') }}
            </h2>
            <a href="{{ route('posts.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Back to News Feed') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Post Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center">
                            <div class="mr-3">
                                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center text-xl font-bold text-white">
                                    {{ substr($post->user->name, 0, 1) }}
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold">{{ $post->user->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        
                        @if ($post->user_id === auth()->id())
                            <div class="flex space-x-2">
                                <a href="{{ route('posts.edit', $post) }}" class="text-blue-600 hover:text-blue-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure you want to delete this post?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mt-4">
                        <p class="text-gray-800 whitespace-pre-line">{{ $post->content }}</p>
                        
                        @if ($post->image)
                            <div class="mt-3">
                                <img src="{{ Storage::url($post->image) }}" alt="Post image" class="max-w-full rounded-lg">
                            </div>
                        @endif
                    </div>
                    
                    <div class="mt-4 flex items-center justify-between border-t border-b py-2">
                        <div class="flex items-center">
                            <span class="text-gray-600 mr-2">{{ $post->reacts->count() }} likes</span>
                        </div>
                        <div>
                            <span class="text-gray-600">{{ $post->comments->count() }} comments</span>
                        </div>
                    </div>
                    
                    <div class="mt-2 flex justify-around">
                        <form action="{{ route('posts.react', $post) }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center px-2 py-1 text-sm {{ $post->reacts->where('user_id', auth()->id())->count() ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600' }} hover:bg-gray-200 rounded-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                </svg>
                                Like
                            </button>
                        </form>
                        
                        <a href="#comments" class="flex items-center px-2 py-1 text-sm bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            Comment
                        </a>
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div id="comments" class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white">
                    <h3 class="text-lg font-semibold mb-4">Comments</h3>
                    
                    <!-- New Comment Form -->
                    <form action="{{ route('comments.store', $post) }}" method="POST" class="mb-6" enctype="multipart/form-data">
                        @csrf
                        <div class="flex items-start">
                            <div class="mr-3">
                                <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center text-sm font-bold text-white">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            </div>
                            <div class="flex-1">
                                <textarea name="content" rows="2" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Write a comment..."></textarea>
                                
                                <div class="mt-2 flex justify-between items-center">
                                    <label class="inline-flex items-center px-3 py-1.5 bg-gray-200 rounded-md font-semibold text-xs text-gray-700 hover:bg-gray-300 active:bg-gray-400 cursor-pointer">
                                        <input type="file" name="image" class="hidden">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Add Photo
                                    </label>
                                    
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-blue-600 border rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Comment
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Comments List -->
                    @if($post->comments->count() > 0)
                        @foreach($post->comments->sortByDesc('created_at') as $comment)
                            <div class="border-t py-4">
                                <div class="flex items-start">
                                    <div class="mr-3">
                                        <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center text-sm font-bold text-white">
                                            {{ substr($comment->user->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="bg-gray-100 rounded-lg p-3">
                                            <div class="flex justify-between items-center">
                                                <h4 class="font-semibold">{{ $comment->user->name }}</h4>
                                                <p class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                                            </div>
                                            <p class="mt-1 text-gray-800">{{ $comment->content }}</p>
                                            
                                            @if ($comment->image)
                                                <div class="mt-2">
                                                    <img src="{{ Storage::url($comment->image) }}" alt="Comment image" class="max-w-full rounded-lg max-h-64">
                                                </div>
                                            @endif
                                        </div>
                                        
                                        @if($comment->user_id === auth()->id())
                                            <div class="mt-1 flex space-x-2">
                                                <a href="{{ route('comments.edit', $comment) }}" class="text-xs bg-blue-50 text-blue-600 hover:bg-blue-100 px-2 py-1 rounded">Edit</a>
                                                <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs bg-red-50 text-red-600 hover:bg-red-100 px-2 py-1 rounded" onclick="return confirm('Are you sure you want to delete this comment?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4 text-gray-500">
                            No comments yet. Be the first to comment!
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 