<x-layouts.app>
    {{-- Make sure Font Awesome is linked in your main layout (e.g., resources/views/layouts/app.blade.php) --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> --}}
    <div class="flex justify-center items-center min-h-screen" style="background-image: url('https://assets.universitas123.com/images/85759/20220620/langkah-dalam-proses-pembuatan-kerajinan-rotan.jpg'); background-size: cover; background-position: center;">
        <div class="card w-96 bg-base-100 shadow-xl bg-opacity-75">
            <div class="card-body items-center text-center">
                <div class="mb-4">
                    <img src="{{ asset('assets/icon/logo-1.png') }}" alt="Logo" class="w-20 h-20 mx-auto">
                </div>
                <form method="POST" action="#" class="w-full">
                    @csrf
                    <div class="form-control w-full max-w-xs mx-auto">
                        <input type="email" name="email" placeholder="EMAIL" class="input input-bordered w-full max-w-xs mb-4 rounded-full text-center" value="{{ old('email') }}" required autofocus />
                        @error('email')
                            <label class="label pb-0">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    <div class="form-control w-full max-w-xs mx-auto">
                        <input type="password" name="password" placeholder="PASSWORD" class="input input-bordered w-full max-w-xs mb-4 rounded-full text-center" required />
                         @error('password')
                            <label class="label pb-0">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="flex justify-center space-x-4 my-4">
                        <button type="button" class="btn btn-circle btn-outline">
                            <i class="fab fa-google"></i>
                        </button>
                        <button type="button" class="btn btn-circle btn-outline">
                            <i class="fab fa-facebook-f"></i>
                        </button>
                    </div>

                    <div class="form-control mt-6 w-full max-w-xs mx-auto">
                        <button type="submit" class="btn btn-primary w-full rounded-full">LOGIN</button>
                    </div>
                </form>
                <div class="mt-6 w-full max-w-xs mx-auto">
                     <a href="{{ route('register') }}" wire:navigate class="btn btn-outline w-full rounded-full">REGISTER</a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>