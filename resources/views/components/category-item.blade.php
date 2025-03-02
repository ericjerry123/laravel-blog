@props(['label', 'slug' => '', 'active' => false, 'color' => 'primary', 'count' => null])

<li>
    <a href="{{ route('posts.index', array_merge(request()->except(['page', 'category']), ['category' => $slug])) }}" 
       class="{{ $active ? 'active' : '' }} flex justify-between items-center">
        <span>{{ $label }}</span>
        @if($count !== null)
            <span class="badge badge-{{ $color }} badge-sm">{{ $count }}</span>
        @endif
    </a>
</li>
