<div class="navbar bg-base-100 shadow-sm">
    {{-- Logo區域 --}}
    <div class="flex-1">
        <a href="/" class="btn btn-ghost text-xl">Blog</a>
    </div>

    {{-- 導航選項區域 --}}
    <div class="flex items-center gap-4">
        @auth
            {{-- 文章管理區域 --}}
            <div class="flex gap-2">
                <a href="{{ route('posts.index') }}" class="btn btn-primary btn-sm">文章列表</a>
                <a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm">新增文章</a>
            </div>

            {{-- 用戶資訊區域 --}}
            <div class="flex items-center gap-2">
                <div class="dropdown dropdown-end">
                    <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                        <div class="w-10 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                            <img alt="用戶頭像" src="https://ui-avatars.com/api/?name=User" />
                        </div>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="ml-2">
                    @csrf
                    @method('POST')
                    <button type="submit" class="btn btn-ghost btn-sm">
                        <i class="fa-solid fa-right-from-bracket mr-2"></i>登出
                    </button>
                </form>
            </div>
        @endauth

        @guest
            {{-- 訪客選項 --}}
            <div class="flex gap-2">
                <a href="/login" class="btn btn-primary btn-sm">登入</a>
                <a href="/register" class="btn btn-primary btn-sm">註冊</a>
            </div>
        @endguest
    </div>
</div>
