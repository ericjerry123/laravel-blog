@props(['post'])

<div class="flex items-center space-x-4 mb-8">
    <div class="avatar">
        <div class="w-12 rounded-full">
            <img src="{{ $post->user->avatar ?? asset('images/default-avatar.png') }}" alt="{{ $post->user->name }}">
        </div>
    </div>
    <div>
        <div class="font-bold text-lg">{{ $post->user->name }}</div>
        <div class="text-sm text-gray-500">{{ $post->user->posts_count ?? 0 }} 篇文章 · {{ $post->user->created_at->diffForHumans() }}加入</div>
    </div>
</div> 