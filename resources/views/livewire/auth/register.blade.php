<div>
    <div class="flex justify-center items-center min-h-screen" style="background-image: url('https://assets.universitas123.com/images/85759/20220620/langkah-dalam-proses-pembuatan-kerajinan-rotan.jpg'); background-size: cover; background-position: center;">
        <div class="card w-96 bg-base-100 shadow-xl bg-opacity-75">
            <div class="card-body items-center text-center">
                <div class="mb-4">
                    <img src="{{ asset('assets/icon/logo-1.png') }}" alt="Logo" class="w-20 h-20 mx-auto">
                </div>
                <h2 class="card-title justify-center text-2xl font-bold mb-2">REGISTER</h2>
                <form wire:submit="register" class="w-full">
                    <div class="form-control w-full max-w-xs mx-auto">
                        <input type="text" wire:model="username" placeholder="USERNAME" class="input input-bordered w-full max-w-xs mb-4 rounded-full text-center" required autofocus />
                        @error('username')
                            <label class="label pb-0">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    <div class="form-control w-full max-w-xs mx-auto">
                        <input type="email" wire:model="email" placeholder="EMAIL" class="input input-bordered w-full max-w-xs mb-4 rounded-full text-center" required />
                        @error('email')
                            <label class="label pb-0">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    <div class="form-control w-full max-w-xs mx-auto">
                        <input type="password" wire:model="password" placeholder="PASSWORD" class="input input-bordered w-full max-w-xs mb-4 rounded-full text-center" required />
                        @error('password')
                            <label class="label pb-0">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="flex justify-center space-x-4 my-4">
                        <a href="{{ route('social.login', 'google') }}" class="btn btn-circle btn-outline">
                            <i class="fab fa-google"></i>
                        </a>
                        <a href="{{ route('social.login', 'facebook') }}" class="btn btn-circle btn-outline">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    </div>

                    <div class="form-control mt-6 w-full max-w-xs mx-auto">
                        <button type="submit" class="btn btn-primary w-full rounded-full">REGISTER</button>
                    </div>
                </form>
                <div class="mt-6 w-full max-w-xs mx-auto">
                     <a href="{{ route('login') }}" wire:navigate class="btn btn-outline w-full rounded-full">Sudah Punya Akun?</a>
                </div>
            </div>
        </div>
    </div>
</div> 