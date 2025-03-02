<x-layouts>
    <div class="min-h-screen bg-base-200 flex items-center">
        <div class="card mx-auto w-full max-w-md shadow-xl bg-base-100">
            <div class="card-body">
                <h2 class="card-title text-2xl font-bold text-center mb-4">註冊新帳號</h2>

                <form action="{{ route('register') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <!-- 姓名 -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">姓名</span>
                        </label>
                        <input type="text" name="name" class="input input-bordered w-full @error('name') input-error @enderror" 
                               value="{{ old('name') }}" required />
                        @error('name')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">電子郵件</span>
                        </label>
                        <input type="email" name="email" class="input input-bordered w-full @error('email') input-error @enderror" 
                               value="{{ old('email') }}" required />
                        @error('email')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- 密碼 -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">密碼</span>
                        </label>
                        <input type="password" name="password" class="input input-bordered w-full @error('password') input-error @enderror" 
                               required />
                        @error('password')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- 確認密碼 -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">確認密碼</span>
                        </label>
                        <input type="password" name="password_confirmation" class="input input-bordered w-full" required />
                    </div>

                    <!-- 註冊按鈕 -->
                    <div class="form-control mt-6">
                        <button type="submit" class="btn btn-primary">註冊</button>
                    </div>

                    <!-- 登入連結 -->
                    <div class="text-center mt-4">
                        <p class="text-sm">
                            已經有帳號了？
                            <a href="/" class="link link-primary">登入</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts>
