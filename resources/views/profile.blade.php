<x-app-layout>
<div class="space-y-8">
    <!-- User Profile Section -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex items-center">
                <img class="h-24 w-24 rounded-full object-cover" src="{{ asset('avatar.png') }}" alt="{{ $user->name }}">
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
                <h3 class="text-xl font-semibold mb-2">Комментарии к профилю</h3>
                <!-- Comments -->
                <x-comments/>

                </div>
            </div>
        </div>

    <!-- User's Posts -->
    <h3 class="text-2xl font-semibold my-4">Посты пользователя</h3>
    <div class="space-y-8">
        @forelse ($user->posts as $post)
            <x-post-card :post="$post" />
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
