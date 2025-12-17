<x-app-layout>
<div x-data="moderationPage()" x-init="fetchItems('posts')" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Панель модерации</h2>

        <!-- Tabs -->
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button @click="switchTab('posts')" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'posts', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'posts' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Посты
                </button>
                <button @click="switchTab('comments')" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'comments', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'comments' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Комментарии
                </button>
            </nav>
        </div>

        <!-- Content -->
        <div class="mt-6">
            <template x-if="loading">
                <p class="text-gray-500">Загрузка...</p>
            </template>

            <template x-if="!loading && items.length === 0">
                 <p class="text-gray-500">Нет элементов для модерации.</p>
            </template>

            <div class="space-y-6">
                 <template x-for="item in items" :key="item.id">
                    <div class="p-4 border rounded-lg">
                        <div x-show="activeTab === 'posts'">
                            <h4 class="font-bold" x-text="item.moderatable.title"></h4>
                            <p class="text-gray-600" x-text="item.moderatable.content"></p>
                        </div>
                         <div x-show="activeTab === 'comments'">
                            <p class="text-gray-600" x-text="item.moderatable.body"></p>
                        </div>
                        <div class="text-xs text-gray-500 mt-2">
                            Автор: <span x-text="item.moderatable.user ? item.moderatable.user.name : 'N/A'"></span>
                        </div>

                        <!-- Moderation Actions -->
                        <div class="mt-4 flex items-center space-x-4">
                            <button @click="accept(item.id)" class="px-3 py-1 bg-green-500 text-white text-sm font-semibold rounded-md hover:bg-green-600">Принять</button>
                            
                            <div x-data="{ open: false }">
                                <button @click="open = true" class="px-3 py-1 bg-red-500 text-white text-sm font-semibold rounded-md hover:bg-red-600">Отклонить</button>
                                <div x-show="open" @click.away="open = false" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                    <div class="bg-white p-6 rounded-lg shadow-xl" @click.stop>
                                        <h5 class="text-lg font-bold mb-2">Причина отклонения</h5>
                                        <textarea x-ref="declineComment" class="w-full border-gray-300 rounded-md" rows="3"></textarea>
                                        <div class="mt-4 flex justify-end space-x-2">
                                            <button @click="open = false" class="px-4 py-2 text-sm rounded-md">Отмена</button>
                                            <button @click="decline(item.id, $refs.declineComment.value); open = false" class="px-4 py-2 bg-red-600 text-white text-sm rounded-md">Подтвердить</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('moderationPage', () => ({
        loading: true,
        activeTab: 'posts',
        items: [],
        
        fetchItems(type) {
            this.loading = true;
            this.items = [];
            const url = type === 'posts' ? '{{ route('moderation.posts.index') }}' : '{{ route('moderation.comments.index') }}';
            
            axios.get(url)
                .then(response => {
                    this.items = response.data;
                })
                .catch(error => console.error(error))
                .finally(() => this.loading = false);
        },

        switchTab(tab) {
            this.activeTab = tab;
            this.fetchItems(tab);
        },

        accept(moderationId) {
            axios.post(`/moderation/${moderationId}/accept`, { comment: 'Принято.' })
                .then(() => {
                    this.items = this.items.filter(item => item.id !== moderationId);
                })
                .catch(error => console.error('Error accepting:', error));
        },

        decline(moderationId, reason) {
            if (!reason || reason.trim() === '') {
                alert('Пожалуйста, укажите причину отклонения.');
                return;
            }
            axios.post(`/moderation/${moderationId}/decline`, { comment: reason })
                .then(() => {
                    this.items = this.items.filter(item => item.id !== moderationId);
                })
                .catch(error => console.error('Error declining:', error));
        }
    }));
});
</script>
</x-app-layout>
