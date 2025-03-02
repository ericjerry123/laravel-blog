@props(['comment', 'post'])

<div class="bg-base-100 p-4 rounded-lg shadow-sm mb-4" id="comment-{{ $comment->id }}">
    <div class="flex justify-between items-start">
        <div class="flex items-start space-x-3">
            <div class="avatar">
                <div class="w-10 rounded-full">
                    <img src="{{ $comment->user->avatar ?? asset('images/default-avatar.png') }}" alt="{{ $comment->user->name }}">
                </div>
            </div>
            <div>
                <div class="font-semibold">{{ $comment->user->name }}</div>
                <div class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</div>
            </div>
        </div>
        
        @if(auth()->check() && auth()->id() === $comment->user_id)
            <div class="dropdown dropdown-end">
                <label tabindex="0" class="btn btn-ghost btn-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                    </svg>
                </label>
                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                    <li>
                        <button class="edit-comment" data-id="{{ $comment->id }}" data-content="{{ $comment->content }}">
                            編輯
                        </button>
                    </li>
                    <li>
                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="delete-comment-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-error">刪除</button>
                        </form>
                    </li>
                </ul>
            </div>
        @endif
    </div>
    
    <div class="mt-3 comment-content">
        {{ $comment->content }}
    </div>
    
    <div class="mt-3 flex items-center space-x-4">
        <button class="comment-like-button btn btn-xs {{ auth()->check() && $comment->isLikedByUser(auth()->id()) ? 'btn-primary' : 'btn-outline btn-primary' }}" 
                data-comment-id="{{ $comment->id }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            <span class="comment-likes-count">{{ $comment->likes_count ?? 0 }}</span> 讚
        </button>
        
        <button class="reply-button btn btn-xs btn-outline" data-comment-id="{{ $comment->id }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
            </svg>
            回覆
        </button>
    </div>
    
    <!-- 回覆表單，默認隱藏 -->
    <div id="reply-form-{{ $comment->id }}" class="reply-form mt-4 hidden">
        <form action="{{ route('comments.store', ['post' => $post->id]) }}" method="POST">
            @csrf
            <input type="hidden" name="post_id" value="{{ $post->id }}">
            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
            
            <div class="mb-2">
                <textarea name="content" rows="2" class="textarea textarea-bordered w-full" 
                    placeholder="回覆 {{ $comment->user->name }}..."></textarea>
            </div>
            
            <div class="flex justify-end space-x-2">
                <button type="button" class="btn btn-sm btn-ghost cancel-reply" data-comment-id="{{ $comment->id }}">
                    取消
                </button>
                <button type="submit" class="btn btn-sm btn-primary">
                    回覆
                </button>
            </div>
        </form>
    </div>
    
    <!-- 回覆列表 -->
    @if($comment->replies && $comment->replies->count() > 0)
        <div class="mt-4 pl-6 border-l-2 border-gray-200">
            @foreach($comment->replies as $reply)
                <div class="bg-base-200 p-3 rounded-lg mb-3" id="comment-{{ $reply->id }}">
                    <div class="flex justify-between items-start">
                        <div class="flex items-start space-x-3">
                            <div class="avatar">
                                <div class="w-8 rounded-full">
                                    <img src="{{ $reply->user->avatar ?? asset('images/default-avatar.png') }}" alt="{{ $reply->user->name }}">
                                </div>
                            </div>
                            <div>
                                <div class="font-semibold">{{ $reply->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        
                        @if(auth()->check() && auth()->id() === $reply->user_id)
                            <div class="dropdown dropdown-end">
                                <label tabindex="0" class="btn btn-ghost btn-xs">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                    </svg>
                                </label>
                                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                                    <li>
                                        <button class="edit-comment" data-id="{{ $reply->id }}" data-content="{{ $reply->content }}">
                                            編輯
                                        </button>
                                    </li>
                                    <li>
                                        <form action="{{ route('comments.destroy', $reply->id) }}" method="POST" class="delete-comment-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-error">刪除</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mt-2 comment-content">
                        {{ $reply->content }}
                    </div>
                    
                    <div class="mt-2 flex items-center space-x-4">
                        <button class="comment-like-button btn btn-xs {{ auth()->check() && $reply->isLikedByUser(auth()->id()) ? 'btn-primary' : 'btn-outline btn-primary' }}" 
                                data-comment-id="{{ $reply->id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <span class="comment-likes-count">{{ $reply->likes_count ?? 0 }}</span> 讚
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div> 