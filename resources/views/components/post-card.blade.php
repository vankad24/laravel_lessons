@props(['post'])

<div
    x-data="postCard({
        post: {{ \App\Http\Resources\PostResource::make(
            $post->load('user', 'category', 'comments.user')
        )->toJson() }},
        authUserId: {{ auth()->id() ?? 'null' }},
        loginRoute: '{{ route('login') }}'
    })"
    class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
>
    <div class="p-6 border-b border-gray-200">
        <!-- header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a :href="post.user.profile_url">
                    <img class="h-10 w-10 rounded-full" src="{{ asset('avatar.png') }}">
                </a>
                <div class="ml-4">
                    <a :href="post.user.profile_url"
                       class="text-sm font-medium"
                       x-text="post.user.name"></a>
                    <div class="text-sm text-gray-500"
                         x-text="post.created_at_human"></div>
                </div>
            </div>

            <div class="flex items-center gap-3 text-sm text-gray-500">
                <span x-text="post.category.name"></span>

                <!-- owner actions -->
                <template x-if="isOwner">
                    <div class="flex gap-2">
                        <button @click="startEdit" x-show="!editing" title="Редактировать">✏️</button>
                        <button @click="deletePost" title="Удалить">🗑</button>
                    </div>
                </template>
            </div>
        </div>

        <!-- title -->
        <h2
            class="mt-4 text-2xl font-bold"
            x-show="!editing"
            x-text="post.title"
        ></h2>

        <input
            x-show="editing"
            x-model="editTitle"
            class="mt-4 w-full border rounded p-2 text-xl font-bold"
        >

        <!-- content -->
        <p
            class="mt-2 text-gray-600"
            x-show="!editing"
            x-html="post.content"
        ></p>

        <textarea
            x-show="editing"
            x-model="editContent"
            rows="4"
            class="mt-2 w-full border rounded p-2"
        ></textarea>

        <!-- edit actions -->
        <div class="mt-4 flex gap-2" x-show="editing">
            <button
                @click="applyEdit"
                class="bg-indigo-600 text-white px-4 py-2 rounded"
            >
                Применить
            </button>

            <button
                @click="cancelEdit"
                class="bg-gray-300 px-4 py-2 rounded"
            >
                Отмена
            </button>
        </div>

        <!-- footer -->
        <div class="mt-4 flex justify-between text-sm text-gray-500">
            <div class="flex items-center space-x-4">
                <!-- views -->
                <span>👁
                    <span x-text="post.views"></span>
                </span>

                <!-- like -->
                <button @click="likePost" class="flex items-center gap-1">
                    <span
                        x-text="post.is_liked_by_user ? '♥' : '♡'"
                        :class="post.is_liked_by_user ? 'text-red-500' : ''"
                        class="text-2xl"
                    ></span>
                    <span x-text="post.likes"></span>
                </button>
            </div>

            <button @click="commentsOpen = !commentsOpen">
                <span x-text="post.comments.length"></span> Комментариев
            </button>
        </div>
    </div>
    <div x-show="commentsOpen">
        <x-comments/>
    </div>
</div>
