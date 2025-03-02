<div class="drawer-side">
    <label for="my-drawer-2" aria-label="close sidebar" class="drawer-overlay"></label>
    <div class="bg-base-100 w-80 min-h-screen pt-20">
        <div class="px-4 flex flex-col gap-4">
            <form action="{{ route('posts.index') }}" method="GET" class="form-control" id="searchForm">
                <div class="input-group">
                    <input type="text" 
                           name="search" 
                           placeholder="搜尋文章..." 
                           class="input input-bordered w-full" 
                           value="{{ request('search') }}"
                           id="searchInput" />
                    @if(request('search'))
                        <button type="button" class="btn btn-square btn-ghost" onclick="clearSearch()">
                            <i class="fas fa-times"></i>
                        </button>
                    @endif
                </div>
            </form>

            <div class="divider">熱門標籤</div>
            @isset($popularTags)
                <x-lable-list :popularTags="$popularTags" />
            @else
                <x-lable-list />
            @endisset
            
            <div class="divider">分類</div>
            @isset($categories)
                <x-category-list :categories="$categories" />
            @else
                <x-category-list />
            @endisset
        </div>
    </div>
</div>

@push('scripts')
<script>
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');
    let searchTimeout;

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            searchForm.submit();
        }, 500);
    });

    function clearSearch() {
        searchInput.value = '';
        searchForm.submit();
    }
</script>
@endpush
