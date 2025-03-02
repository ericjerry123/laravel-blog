import './bootstrap';

// 處理文章列表的無限滾動
document.addEventListener('DOMContentLoaded', function() {
    let nextPage = null;
    let loading = false;
    const postList = document.querySelector('.flex.flex-col.gap-8');
    
    // 初始化下一頁 URL
    if (postList) {
        nextPage = document.querySelector('.pagination a[rel="next"]')?.href;
        
        // 如果存在分頁，隱藏它（因為我們使用無限滾動）
        const pagination = document.querySelector('.pagination');
        if (pagination) {
            pagination.style.display = 'none';
        }
    }
    
    // 監聽滾動事件
    window.addEventListener('scroll', function() {
        if (loading || !nextPage || !postList) return;
        
        // 檢查是否滾動到頁面底部
        if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 500) {
            loading = true;
            
            // 顯示加載指示器
            const loadingIndicator = document.createElement('div');
            loadingIndicator.className = 'text-center py-4';
            loadingIndicator.innerHTML = '<span class="loading loading-dots loading-md"></span>';
            postList.appendChild(loadingIndicator);
            
            // 發送 AJAX 請求獲取下一頁
            fetch(nextPage, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                // 移除加載指示器
                loadingIndicator.remove();
                
                // 添加新文章到列表
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = data.html;
                
                while (tempDiv.firstChild) {
                    postList.appendChild(tempDiv.firstChild);
                }
                
                // 更新下一頁 URL
                nextPage = data.nextPageUrl;
                loading = false;
            })
            .catch(error => {
                console.error('加載更多文章時出錯:', error);
                loadingIndicator.remove();
                loading = false;
            });
        }
    });
});
