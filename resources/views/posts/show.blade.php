<x-layouts>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <div class="drawer lg:drawer-open">
        <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content">
            <!-- 主要內容區域 -->
            <div class="max-w-4xl mx-auto px-4 py-8">
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

                <!-- 文章標題 -->
                <h1 class="text-3xl font-bold mb-4">{{ $post->title }}</h1>
                
                <!-- 文章元數據 -->
                <x-post-meta :post="$post" />
                
                <!-- 文章作者 -->
                <x-post-author :post="$post" />
                
                <!-- 文章內容 -->
                <x-post-content :post="$post" />
                
                <!-- 文章操作 -->
                <x-post-actions :post="$post" />
                
                <!-- 評論區 -->
                <div id="comments-section" class="mt-12">
                    <h2 class="text-2xl font-bold mb-6">評論 ({{ $post->comments_count }})</h2>
                    
                    @auth
                        <x-comment-form :post="$post" />
                    @else
                        <div class="bg-base-200 p-4 rounded-lg mb-6 text-center">
                            <p>請 <a href="{{ route('login') }}" class="text-primary">登錄</a> 後發表評論</p>
                        </div>
                    @endauth
                    
                    <!-- 評論列表 -->
                    <x-comment-list :comments="$post->comments->where('parent_id', null)" :post="$post" />
                </div>
            </div>
        </div>
        
        <!-- 側邊欄 -->
        <x-sidebar :popularTags="$popularTags ?? collect()" :categories="$categories ?? collect()" />
    </div>
    
    <!-- 文章相關腳本 -->
    <x-post-scripts :post="$post" />
</x-layouts>