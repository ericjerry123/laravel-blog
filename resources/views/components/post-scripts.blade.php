@props(['post'])

<script>
    // 點讚功能
    $(document).ready(function() {
        $('#like-button').click(function() {
            const postId = $(this).data('post-id');
            
            $.ajax({
                url: "{{ route('posts.toggle-like', $post->id) }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // 更新點讚數
                    $('.likes-count').text(response.data.likes_count);
                    
                    // 更新按鈕樣式
                    if (response.data.is_liked) {
                        $('#like-button').removeClass('btn-outline').addClass('btn-primary');
                    } else {
                        $('#like-button').removeClass('btn-primary').addClass('btn-outline btn-primary');
                    }
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 401) {
                        // 未登錄用戶
                        alert('請先登錄後再點讚');
                    } else {
                        console.error("點讚失敗:", error);
                    }
                }
            });
        });
        
        // 評論點讚功能
        $('.comment-like-button').click(function() {
            const commentId = $(this).data('comment-id');
            const $button = $(this);
            const $likesCount = $button.find('.comment-likes-count');
            
            $.ajax({
                url: "{{ route('comments.toggle-like', ['comment' => ':commentId']) }}".replace(':commentId', commentId),
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function(response) {
                    // 更新點讚數
                    $likesCount.text(response.data.likes_count);
                    
                    // 更新按鈕樣式
                    if (response.data.is_liked) {
                        $button.removeClass('btn-outline-primary').addClass('btn-primary');
                    } else {
                        $button.removeClass('btn-primary').addClass('btn-outline-primary');
                    }
                },
                error: function(xhr, status, error) {
                    console.log("錯誤狀態碼:", xhr.status);
                    console.log("錯誤訊息:", xhr.responseText);
                    
                    if (xhr.status === 401) {
                        // 未登錄用戶
                        alert('請先登錄後再點讚');
                        window.location.href = "{{ route('login') }}";
                    } else if (xhr.status === 403) {
                        // CSRF 令牌問題或權限問題
                        alert('權限錯誤，請重新整理頁面後再試');
                    } else if (xhr.status === 404) {
                        // 評論不存在
                        alert('評論不存在或已被刪除');
                    } else {
                        console.error("評論點讚失敗:", error);
                        alert('操作失敗，請稍後再試');
                    }
                }
            });
        });
    });
    
    // 閱讀數計時器
    $(document).ready(function() {
        // 文章ID
        const postId = {{ $post->id }};
        // 是否已經增加過閱讀數
        let viewIncremented = false;
        
        // 30秒後發送請求增加閱讀數
        setTimeout(function() {
            if (!viewIncremented) {
                incrementViews();
            }
        }, 30000); // 30秒 = 30000毫秒
        
        // 增加閱讀數的函數
        function incrementViews() {
            $.ajax({
                url: "{{ route('posts.increment-views', $post->id) }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log("閱讀數更新成功:", response);
                    // 更新頁面上的閱讀數
                    if (response.data && response.data.views_count !== undefined) {
                        $('.views-count').text(response.data.views_count + ' 閱讀');
                    }
                    viewIncremented = true;
                },
                error: function(xhr, status, error) {
                    console.error("閱讀數更新失敗:", error);
                }
            });
        }
        
        // 如果用戶離開頁面前已經停留超過 30 秒但還沒有增加閱讀數，則在離開前增加
        $(window).on('beforeunload', function() {
            if (!viewIncremented) {
                // 使用同步請求確保在頁面關閉前發送
                $.ajax({
                    url: "{{ route('posts.increment-views', $post->id) }}",
                    type: "POST",
                    async: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            }
        });
    });
    
    // 評論相關功能
    $(document).ready(function() {
        // 顯示回覆表單
        $('.reply-button').click(function() {
            const commentId = $(this).data('comment-id');
            $(`#reply-form-${commentId}`).toggleClass('hidden');
        });
        
        // 取消回覆
        $('.cancel-reply').click(function() {
            const commentId = $(this).data('comment-id');
            $(`#reply-form-${commentId}`).addClass('hidden');
        });
        
        // 編輯評論
        $('.edit-comment').click(function() {
            const commentId = $(this).data('id');
            const commentContent = $(this).data('content');
            
            // 創建編輯表單
            const $commentElement = $(`#comment-${commentId}`).find('.comment-content');
            const originalContent = $commentElement.html();
            
            $commentElement.html(`
                <form class="edit-comment-form" data-id="${commentId}" data-original="${originalContent}">
                    <textarea class="textarea textarea-bordered w-full mb-2">${commentContent}</textarea>
                    <div class="flex justify-end space-x-2">
                        <button type="button" class="btn btn-sm btn-ghost cancel-edit">取消</button>
                        <button type="submit" class="btn btn-sm btn-primary">更新</button>
                    </div>
                </form>
            `);
            
            // 取消編輯
            $commentElement.find('.cancel-edit').click(function() {
                $commentElement.html(originalContent);
            });
            
            // 提交編輯
            $commentElement.find('.edit-comment-form').submit(function(e) {
                e.preventDefault();
                
                const newContent = $(this).find('textarea').val();
                
                $.ajax({
                    url: `/comments/${commentId}`,
                    type: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        content: newContent
                    },
                    success: function(response) {
                        $commentElement.html(newContent);
                    },
                    error: function(xhr, status, error) {
                        alert('更新評論失敗: ' + error);
                        $commentElement.html(originalContent);
                    }
                });
            });
        });
        
        // 刪除評論確認
        $('.delete-comment-form').submit(function(e) {
            if (!confirm('確定要刪除這條評論嗎？')) {
                e.preventDefault();
            }
        });
    });
    
    // 分享功能
    function sharePost() {
        if (navigator.share) {
            navigator.share({
                title: "{{ $post->title }}",
                text: "{{ Str::limit(strip_tags($post->content), 100) }}",
                url: window.location.href
            })
            .then(() => console.log('分享成功'))
            .catch((error) => console.log('分享失敗', error));
        } else {
            // 複製鏈接到剪貼板
            const dummy = document.createElement('input');
            document.body.appendChild(dummy);
            dummy.value = window.location.href;
            dummy.select();
            document.execCommand('copy');
            document.body.removeChild(dummy);
            
            alert('鏈接已複製到剪貼板');
        }
    }
</script> 