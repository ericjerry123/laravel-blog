<x-layouts>
    <div class="min-h-screen bg-base-200 flex items-center">
        <div class="card mx-auto w-full max-w-md shadow-xl bg-base-100">
            <div class="card-body">
                <h2 class="card-title text-2xl font-bold text-center mb-4">登入</h2>

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf
                    
                    <!-- Email 輸入欄位 -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">電子郵件</span>
                        </label>
                        <input type="email" 
                               name="email" 
                               class="input input-bordered @error('email') input-error @enderror" 
                               value="{{ old('email') }}" 
                               required 
                               autocomplete="email" 
                               autofocus>
                        @error('email')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- 密碼輸入欄位 -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">密碼</span>
                        </label>
                        <input type="password" 
                               name="password" 
                               class="input input-bordered @error('password') input-error @enderror" 
                               required 
                               autocomplete="current-password">
                        @error('password')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- 登入按鈕 -->
                    <div class="form-control mt-6">
                        <button type="submit" class="btn btn-primary">登入</button>
                    </div>

                    <!-- 其他連結 -->
                    <div class="flex justify-between text-sm mt-4">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="link link-hover">
                                忘記密碼？
                            </a>
                        @endif
                        
                        <a href="{{ route('register') }}" class="link link-hover">
                            還沒有帳號？立即註冊
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts>
