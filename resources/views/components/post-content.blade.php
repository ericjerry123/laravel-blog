@props(['post'])

<div class="prose prose-lg max-w-none">
    {!! $post->content !!}
</div>

@if($post->tags->count() > 0)
    <div class="mt-8">
        <h3 class="text-lg font-semibold mb-2">標籤</h3>
        <div class="flex flex-wrap gap-2">
            @foreach($post->tags as $tag)
                <a href="{{ route('posts.index', ['tag' => $tag->slug]) }}" 
                   class="badge badge-primary">
                    {{ $tag->name }}
                </a>
            @endforeach
        </div>
    </div>
@endif 