<x-app-layout>
<div class="space-y-8">
    <!-- User Profile Section -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex items-center">
                <img class="h-24 w-24 rounded-full object-cover" src="https://i.pravatar.cc/300?u={{ $user->id }}" alt="{{ $user->name }}">
                <div class="ml-6">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h1>
                    <p class="text-sm text-gray-500">Зарегистрирован {{ $user->created_at->format('d M, Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Comments on Profile -->
                <div x-data="commentSection({
                commentableId: {{ $user->id }},
                commentableType: 'user',
                initialComments: {{ \App\Http\Resources\CommentResource::collection($user->comments)->toJson() }},
                isUserAuth: {{ auth()->check() ? 'true' : 'false' }},
                loginRoute: '{{ route('login') }}',
                profileShowBaseUrl: '{{ route('profile.show', 0) }}' // Pass 0 as a dummy parameter
            })" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-xl font-semibold">Комментарии к профилю</h3>
                <!-- New Comment Form -->
                <div class="mt-4">
                     <template x-if="isUserAuth">
                        <form @submit.prevent="addComment">
                            <textarea x-model="newCommentBody" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" rows="3" placeholder="Оставить комментарий..."></textarea>
                            <button type="submit" class="mt-2 px-4 py-2 bg-indigo-600 text-black font-semibold rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Отправить
                            </button>
                        </form>
                    </template>
                    <template x-if="!isUserAuth">
                        <p class="text-sm text-gray-500">
                            Чтобы оставить комментарий, пожалуйста, <a :href="loginRoute" class="underline text-indigo-600">войдите</a>.
                        </p>
                    </template>
                </div>
                <!-- Existing Comments -->
                <div class="mt-6 space-y-4">
                    <template x-for="comment in comments" :key="comment.id">
                        <div class="flex items-start">
                            <a :href="profileShowBaseUrl.replace('0', comment.user.id)">
                               <img class="h-8 w-8 rounded-full object-cover" :src="`https://i.pravatar.cc/150?u=${comment.user.id}`" :alt="comment.user.name">
                            </a>
                            <div class="ml-3">
                                <div class="text-sm">
                                    <a :href="profileShowBaseUrl.replace('0', comment.user.id)" class="font-medium text-gray-900" x-text="comment.user.name"></a>
                                </div>
                                <p class="mt-1 text-gray-600" x-text="comment.body"></p>
                                <div class="mt-1 text-xs text-gray-500" x-text="comment.created_at_human"></div>
                            </div>
                        </div>
                    </template>
                     <template x-if="comments.length === 0">
                        <p class="text-gray-500">Комментариев пока нет.</p>
                    </template>
                </div>
            </div>
        </div>

    <!-- User's Posts -->
    <div>
        <h3 class="text-2xl font-semibold mb-4">Посты пользователя</h3>
        @forelse ($user->posts as $post)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                     <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <a href="{{ route('profile.show', $post->user->id) }}">
                                <img class="h-10 w-10 rounded-full object-cover" src="https://i.pravatar.cc/150?u={{ $post->user->id }}" alt="{{ $post->user->name }}">
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
                </div>
            </div>
        @empty
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <p class="p-6 text-center text-gray-500">Этот пользователь еще не создал ни одного поста.</p>
            </div>
        @endforelse
    </div>
</div>

<script>
// This Alpine component is reusable for any comment section
document.addEventListener('alpine:init', () => {
    Alpine.data('commentSection', ({ commentableId, commentableType, initialComments, isUserAuth, loginRoute, profileShowBaseUrl }) => ({
        commentableId: commentableId,
        commentableType: commentableType,
        comments: initialComments,
        commentsOpen: true, // Open by default on profile page
        newCommentBody: '',
        isUserAuth: isUserAuth,
        loginRoute: loginRoute,
        profileShowBaseUrl: profileShowBaseUrl,

        profileShowUrl(userId) {
            return this.profileShowBaseUrl.replace('0', userId);
        },

        addComment() {
            if (this.newCommentBody.trim() === '') return;

            axios.post('{{ route('comments.store') }}', {
                body: this.newCommentBody,
                commentable_id: this.commentableId,
                commentable_type: this.commentableType
            })
            .then(response => {
                this.comments.unshift(response.data.data);
                this.newCommentBody = '';
            })
            .catch(error => {
                console.error('Error posting comment:', error);
            });
        }
    }));
});
</script>
</x-app-layout>
