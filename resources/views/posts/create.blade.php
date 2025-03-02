<x-layouts>
    <div class="max-w-4xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">創建新文章</h1>
        
        <form action="{{ route('posts.store') }}" method="POST" class="bg-base-100 p-6 rounded-lg shadow-sm">
            @csrf
            
            <!-- 標題 -->
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium mb-1">標題</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" 
                       class="input input-bordered w-full" required>
                @error('title')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- 分類 -->
            <div class="mb-4">
                <label for="category_id" class="block text-sm font-medium mb-1">分類</label>
                <select name="category_id" id="category_id" class="select select-bordered w-full" required>
                    <option value="">選擇分類</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- 標籤 -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">標籤</label>
                <div class="flex flex-wrap gap-2 mb-2">
                    @foreach($tags as $tag)
                        <label class="flex items-center gap-1 cursor-pointer">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" 
                                   class="checkbox checkbox-sm checkbox-primary"
                                   {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                            <span>{{ $tag->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('tags')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- 內容 -->
            <div class="mb-6">
                <label for="content" class="block text-sm font-medium mb-1">內容</label>
                <textarea name="content" id="content" rows="10" 
                          class="textarea textarea-bordered w-full" required>{{ old('content') }}</textarea>
                @error('content')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- 按鈕 -->
            <div class="flex justify-end gap-2">
                <a href="{{ route('posts.index') }}" class="btn btn-ghost">取消</a>
                <button type="submit" class="btn btn-primary">發布文章</button>
            </div>
        </form>
    </div>
    
    <!-- 引入 TinyMCE 編輯器 -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#content',
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            height: 400
        });
    </script>
</x-layouts>
