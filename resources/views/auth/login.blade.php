<x-layouts.app>
    <div class="flex justify-center items-center min-h-screen bg-base-200">
        <div class="card w-96 bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title justify-center">Login</h2>
                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Username</span>
                        </label>
                        <input type="text" name="username" placeholder="username" class="input input-bordered" value="{{ old('username') }}" required autofocus />
                        @error('username')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Password</span>
                        </label>
                        <input type="password" name="password" placeholder="password" class="input input-bordered" required />
                    </div>
                    <div class="form-control mt-6">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
                <div class="text-center mt-4">
                    <a href="{{ route('register') }}" class="text-sm link link-hover">Don't have an account? Register</a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>