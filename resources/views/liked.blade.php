<x-app-layout>
<div class="space-y-8">
    <h2 class="text-3xl font-bold text-gray-900">Понравившиеся посты</h2>

    @forelse ($posts as $post)
        <x-post-card :post="$post" />
    @empty
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-center text-gray-500">
                Вы еще не лайкнули ни одного поста.
            </div>
        </div>
    @endforelse

</div>
</x-app-layout>
