<x-app-layout>
<div class="space-y-8">
    <h2 class="text-3xl font-bold text-gray-900">Понравившиеся посты</h2>

    @forelse ($posts as $post)
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <a href="{{ route('profile.show', $post->user->id) }}">
                            <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('avatar.png') }}" alt="{{ $post->user->name }}">
                        </a>
                        <div class="ml-4">
                            <a href="{{ route('profile.show', $post->user->id) }}" class="text-sm font-medium text-gray-900">{{ $post->user->name }}</a>
                            <div class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    <div class="text-sm text-gray-500">{{ $post->category->name }}</div>
                </div>

                <h2 class="mt-4 text-2xl font-bold text-gray-900">{{ $post->title }}</h2>
                <p class="mt-2 text-gray-600">
                    {{ Str::limit($post->content, 300) }}
                </p>

                 <div class="mt-4 flex items-center justify-start space-x-4 text-sm text-gray-500">
                    <span>
                        <svg class="inline-block h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <span>{{ $post->views }}</span>
                    </span>
                    <span>
                        <svg class="inline-block h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        <span>{{ $post->likes }}</span>
                    </span>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-center text-gray-500">
                Вы еще не лайкнули ни одного поста.
            </div>
        </div>
    @endforelse

    <div class="mt-8">
        {{ $posts->links() }}
    </div>
</div>
</x-app-layout>
