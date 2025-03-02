@props(['post'])

<div class="bg-base-200 p-4 rounded-lg mb-6">
    <h3 class="text-lg font-semibold mb-4">發表評論</h3>
    <form id="comment-form" action="{{ route('comments.store', ['post' => $post->id]) }}" method="POST">
        @csrf
        <input type="hidden" name="post_id" value="{{ $post->id }}">
        <input type="hidden" name="parent_id" id="parent_id" value="">
        
        <div class="mb-4">
            <textarea name="content" id="comment-content" rows="4" 
                class="textarea textarea-bordered w-full" 
                placeholder="請輸入您的評論..."></textarea>
            @error('content')
                <span class="text-error text-sm">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="flex justify-end">
            <button type="submit" class="btn btn-primary">
                發表評論
            </button>
        </div>
    </form>
</div> 