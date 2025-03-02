<x-layouts>
    <div class="drawer lg:drawer-open">
        <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content">
            <!-- 主要內容區域 -->
            <div class="max-w-4xl mx-auto">
                <div class="flex justify-between items-center mb-6">
                    <label for="my-drawer-2" class="btn btn-primary drawer-button lg:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </label>
                    <x-dropdown />
                </div>

                <!-- 排序選項 -->
                <div class="flex justify-end mb-4">
                    <div class="dropdown dropdown-end">
                        <label tabindex="0" class="btn btn-sm m-1">
                            <span>排序方式</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </label>
                        <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                            <li><a href="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}" class="{{ request('sort', 'latest') === 'latest' ? 'active' : '' }}">最新發布</a></li>
                            <li><a href="{{ request()->fullUrlWithQuery(['sort' => 'popular']) }}" class="{{ request('sort') === 'popular' ? 'active' : '' }}">最受歡迎</a></li>
                            <li><a href="{{ request()->fullUrlWithQuery(['sort' => 'most_commented']) }}" class="{{ request('sort') === 'most_commented' ? 'active' : '' }}">評論最多</a></li>
                        </ul>
                    </div>
                </div>

                <!-- 文章列表 -->
                <div class="flex flex-col gap-8">
                    @foreach ($posts as $post)
                        <x-post-card :post="$post" />
                    @endforeach
                </div>

                <!-- 分頁 -->
                <div class="mt-8 flex justify-center">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>

        <!-- 側邊欄 -->
        <x-sidebar :popularTags="$popularTags ?? collect()" :categories="$categories ?? collect()" />
    </div>

    <!-- 添加 Font Awesome 圖標 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</x-layouts>
