<div class="flex bg-white p-4 rounded-xl shadow">
    <a :href="comment.user.profile_url">
        <img class="h-8 w-8 rounded-full" src="{{ asset('avatar.png') }}">
    </a>

    <div class="ml-3">
        <a class="font-medium" x-text="comment.user.name"></a>
        <p class="text-gray-600" x-text="comment.body"></p>
        <div class="text-xs text-gray-500"
             x-text="comment.created_at_human"></div>
    </div>
</div>
