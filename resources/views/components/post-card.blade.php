<article class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow">
    <div class="card-body">
        <!-- 作者資訊 -->
        <div class="flex items-center gap-3 mb-4">
            <div class="avatar">
                <div class="w-10 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                    <img
                        src="{{ $post->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name ?? 'Anonymous') }}" />
                </div>
            </div>
            <div>
                <h3 class="font-semibold">{{ $post->user->name ?? 'Anonymous' }}</h3>
                <p class="text-sm text-base-content/70">{{ $post->created_at->diffForHumans() }}</p>
            </div>
        </div>

        <!-- 文章標題和內容 -->
        <a href="{{ route('posts.show', $post) }}" class="hover:no-underline group">
            <h2 class="card-title text-2xl mb-2 group-hover:text-primary transition-colors">{{ $post->title }}</h2>
            <p class="text-base-content/70 line-clamp-3 mb-4">{{ Str::limit($post->content, 200) }}</p>
        </a>

        <!-- 文章分類 -->
        <div class="mb-2">
            @if($post->category)
                <a href="{{ route('posts.index', ['category' => $post->category->slug]) }}" class="hover:no-underline">
                    <div class="badge badge-{{ $post->category->color }} badge-outline">
                        {{ $post->category->name }}
                    </div>
                </a>
            @else
                <div class="badge badge-ghost badge-outline">無分類</div>
            @endif
        </div>

        <!-- 文章標籤 -->
        <div class="flex gap-2 mb-4">
            @if($post->tags->count() > 0)
                @foreach($post->tags as $tag)
                    <a href="{{ route('posts.index', ['tag' => $tag->slug]) }}" class="hover:no-underline">
                        <div class="badge badge-{{ $loop->index % 3 == 0 ? 'primary' : ($loop->index % 3 == 1 ? 'secondary' : 'accent') }}">
                            {{ $tag->name }}
                        </div>
                    </a>
                @endforeach
            @else
                <div class="badge badge-ghost">無標籤</div>
            @endif
        </div>

        <!-- 文章數據 -->
        <div class="flex items-center gap-6 text-sm text-base-content/70">
            <span class="flex items-center gap-1">
                <i class="fas fa-eye"></i> {{ $post->views_count ?? 0 }}
            </span>
            <span class="flex items-center gap-1">
                <i class="fas fa-heart"></i> {{ $post->likes_count ?? 0 }}
            </span>
            <span class="flex items-center gap-1">
                <i class="fas fa-comment"></i> {{ $post->allComments->count() ?? 0 }}
            </span>
        </div>
    </div>
</article>
