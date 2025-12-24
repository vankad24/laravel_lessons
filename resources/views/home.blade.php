<x-app-layout>
<div class="space-y-8">
    <!-- New Post Form -->
    <div class="bg-white shadow-sm sm:rounded-lg p-6" x-data="newPostForm()">
    <h2 class="text-xl font-bold mb-4">Создать новый пост</h2>

    <form @submit.prevent="submitPost">
        <div class="mb-4">
            <label>Заголовок</label>
            <input type="text" x-model="title" class="w-full border p-2">
        </div>

        <div class="mb-4">
            <label>Контент</label>
            <textarea x-model="content" class="w-full border p-2"></textarea>
        </div>

        <div class="mb-4">
            <label>Категория</label>
            <select x-model="category_id" class="w-full border p-2">
                <option value="">-- выберите --</option>
                @foreach(\App\Models\Category::all() as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label>Теги</label>
            <select x-model="tags" multiple class="w-full border p-2">
                @foreach(\App\Models\Tag::all() as $tag)
                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label>Статус</label>
            <select x-model="status" class="w-full border p-2">
                <option value="published">published</option>
                <option value="scheduled">scheduled</option>
            </select>
        </div>

        <div class="mb-4" x-show="status !== 'published'">
            <label>Дата публикации</label>
            <input type="datetime-local"
                   x-model="published_at"
                   class="w-full border p-2">
        </div>


        <button class="bg-indigo-600 text-white px-4 py-2 rounded">
            Создать пост
        </button>
    </form>
</div>


    <!-- Posts -->
    @forelse ($posts as $post)
        <x-post-card :post="$post" />
    @empty
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-center text-gray-500">
                Пока нет ни одного поста.
            </div>
        </div>
    @endforelse

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('newPostForm', () => ({
        title: '',
        content: '',
        category_id: '',
        tags: [],
        status: 'published',
        published_at: null,

        submitPost() {
            const data = {
                title: this.title,
                content: this.content,
                category_id: this.category_id,
                tags: this.tags,
                status: this.status,
            };

            if (this.status !== 'published') {
                data.published_at = this.published_at;
            }

            axios.post(
                '{{ route('posts.store') }}',
                data,
                {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }
            ).then(() => window.location.reload())
             .catch(err => console.error(err.response?.data || err));
        }
    }));
});
</script>
</x-app-layout>
