<div class="flex flex-col gap-2 p-2">
    <h3 class="font-bold mb-3">熱門標籤</h3>
    <div class="flex flex-wrap gap-2">
        @if(isset($popularTags) && $popularTags->isNotEmpty())
            <!-- 標籤數量: {{ $popularTags->count() }} -->
            @foreach($popularTags as $tag)
                <a href="{{ route('posts.index', ['tag' => $tag->slug]) }}" class="hover:no-underline">
                    <x-lable-item color="{{ request('tag') == $tag->slug ? 'primary' : $tag->color }}" :label="$tag->name" />
                </a>
            @endforeach
        @else
            <p class="text-sm text-base-content/70">暫無標籤 ({{ isset($popularTags) ? '已設置但為空' : '未設置' }})</p>
        @endif
    </div>
</div>
