<div class="p-6 bg-gray-50 border-t">
    <template x-if="isUserAuth">
        <form @submit.prevent="addComment">
            <textarea
                x-model="newCommentBody"
                class="w-full rounded-md"
                rows="3"
                placeholder="Написать комментарий..."
            ></textarea>

            <button class="mt-2 px-4 py-2 bg-indigo-100 rounded">
                Отправить
            </button>
        </form>
    </template>

    <template x-if="!isUserAuth">
        <p class="text-sm text-gray-500">
            <a :href="loginRoute" class="underline">Войдите</a>, чтобы комментировать
        </p>
    </template>

    <div class="mt-6 space-y-4">
        <template x-for="comment in comments" :key="comment.id">
            <x-comment-card />
        </template>
        <template x-if="comments.length === 0">
            <p class="text-gray-500">Комментариев пока нет.</p>
        </template>
    </div>
</div>
