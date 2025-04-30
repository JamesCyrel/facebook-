<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-semibold mb-4">{{ __("Welcome to your Facebook Wall!") }}</h2>
                    <p class="mb-4">{{ __("You're logged in! What would you like to do today?") }}</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <a href="{{ route('posts.index') }}" class="block p-6 bg-blue-100 hover:bg-blue-200 rounded-lg shadow-md transition duration-150">
                            <h3 class="text-xl font-semibold text-blue-700 mb-2">📱 News Feed</h3>
                            <p class="text-blue-600">View posts from everyone, create your own posts, comment, and like.</p>
                        </a>
                        
                        <a href="{{ route('posts.create') }}" class="block p-6 bg-green-100 hover:bg-green-200 rounded-lg shadow-md transition duration-150">
                            <h3 class="text-xl font-semibold text-green-700 mb-2">✍️ Create Post</h3>
                            <p class="text-green-600">Share what's on your mind or upload a photo.</p>
                        </a>
                        
                        <a href="{{ route('profile.edit') }}" class="block p-6 bg-purple-100 hover:bg-purple-200 rounded-lg shadow-md transition duration-150">
                            <h3 class="text-xl font-semibold text-purple-700 mb-2">👤 Your Profile</h3>
                            <p class="text-purple-600">Update your profile information and password.</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
