<x-app-layout>
<div class="space-y-8">
    @forelse ($posts as $post)
        <x-post-card :post="$post" />
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
</x-app-layout>
