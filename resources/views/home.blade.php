<x-app-layout>
<div class="space-y-8">
    @forelse ($posts as $post)
        <div
            x-data="postCard({
                post: {{ \App\Http\Resources\PostResource::make($post->load('user', 'category', 'comments.user'))->toJson() }},
                isUserAuth: {{ auth()->check() ? 'true' : 'false' }},
                loginRoute: '{{ route('login') }}'
            })"
            class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
        >
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <a :href="post.user.profile_url">
                            <img class="h-10 w-10 rounded-full object-cover" :src="'https://i.pravatar.cc/150?u=' + post.user.id" :alt="post.user.name">
                        </a>
                        <div class="ml-4">
                            <a :href="post.user.profile_url" class="text-sm font-medium text-gray-900" x-text="post.user.name"></a>
                            <div class="text-sm text-gray-500" x-text="post.created_at_human"></div>
                        </div>
                    </div>
                    <div class="text-sm text-gray-500" x-text="post.category.name"></div>
                </div>

                <h2 class="mt-4 text-2xl font-bold text-gray-900" x-text="post.title"></h2>
                <p class="mt-2 text-gray-600" x-html="post.content"></p>

                <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
                    <div class="flex items-center space-x-4">
                        <span class="flex items-center">
                            <svg class="inline-block h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <span x-text="post.views"></span>
                        </span>
                        <button @click="likePost" class="flex items-center focus:outline-none">
                            <svg class="inline-block h-5 w-5 mr-1" :class="{'text-red-500': post.is_liked_by_user}" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            <span x-text="post.likes"></span>
                        </button>
                    </div>
                    <button @click="commentsOpen = !commentsOpen" class="focus:outline-none">
                        <span x-text="post.comments.length"></span> Комментарии
                    </button>
                </div>
            </div>

            <!-- Comments -->
            <div x-show="commentsOpen" class="p-6 bg-gray-50 border-t border-gray-200">
                <!-- New Comment Form -->
                <div class="mt-4">
                    <template x-if="isUserAuth">
                        <form @submit.prevent="addComment">
                            <textarea x-model="newCommentBody" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" rows="3" placeholder="Написать комментарий..."></textarea>
                            <button type="submit" class="mt-2 px-4 py-2 bg-indigo-600 text-black bg-gray font-semibold rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
                    <template x-for="comment in post.comments" :key="comment.id">
                        <div class="flex items-start">
                            <a :href="comment.user.profile_url">
                                <img class="h-8 w-8 rounded-full object-cover" :src="`https://i.pravatar.cc/150?u=${comment.user.id}`" :alt="comment.user.name">
                            </a>
                            <div class="ml-3">
                                <div class="text-sm">
                                    <a :href="comment.user.profile_url" class="font-medium text-gray-900" x-text="comment.user.name"></a>
                                </div>
                                <p class="mt-1 text-gray-600" x-text="comment.body"></p>
                                <div class="mt-1 text-xs text-gray-500" x-text="comment.created_at_human"></div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-center text-gray-500">
                Пока нет ни одного поста.
            </div>
        </div>
    @endforelse

    <div class="mt-8">
        {{ $posts->links() }}
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('postCard', ({ post, isUserAuth, loginRoute }) => ({
        post: post,
        commentsOpen: true,
        newCommentBody: '',
        isUserAuth: isUserAuth,
        loginRoute: loginRoute,


        addComment() {
            if (this.newCommentBody.trim() === '') return;

            axios.post('{{ route('comments.store') }}', {
                body: this.newCommentBody,
                commentable_id: this.post.id,
                commentable_type: 'post'
            })
            .then(response => {
                this.post.comments.unshift(response.data.data);
                this.newCommentBody = '';
            })
            .catch(error => {
                console.error('Error posting comment:', error);
            });
        },

        likePost() {
            if (!this.isUserAuth) {
                window.location.href = this.loginRoute;
                return;
            }

            axios.post(`/api/posts/${this.post.id}/like`)
            .then(response => {
                this.post.likes = response.data.data.likes;
                this.post.is_liked_by_user = response.data.data.is_liked_by_user;
            })
            .catch(error => {
                console.error('Error liking post:', error);
            });
        }
    }));
});
</script>
</x-app-layout>
