@props(['comments', 'post'])

<div class="space-y-6">
    @forelse($comments as $comment)
        <x-comment-item :comment="$comment" :post="$post" />
    @empty
        <div class="text-center py-6 text-gray-500">
            暫無評論，成為第一個評論的人吧！
        </div>
    @endforelse
</div> 