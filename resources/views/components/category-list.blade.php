@props(['categories' => null])

<ul class="menu bg-base-200 w-full rounded-box">
    <x-category-item label="全部文章" slug="" :active="!request('category')" />
    
    @if($categories && $categories->count() > 0)
        @foreach($categories as $category)
            <x-category-item 
                :label="$category->name" 
                :slug="$category->slug" 
                :color="$category->color"
                :active="request('category') === $category->slug" 
                :count="$category->posts_count" 
            />
        @endforeach
    @else
        <li><span class="text-gray-500 px-4 py-2">暫無分類</span></li>
    @endif
</ul>