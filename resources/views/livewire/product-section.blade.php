<div class="bg-base-200 py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            @for ($i = 0; $i < 6; $i++)
                <div class="bg-base-100 rounded-lg shadow-lg overflow-hidden">
                    <div class="p-6 text-center">
                        <div class="flex justify-center mb-2">
                            @for ($s = 0; $s < 4; $s++)
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            @endfor
                            <svg class="w-5 h-5 text-base-content/50" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-base-content mb-1">Lampu tidur hias</h3>
                        <p class="text-base-content mb-4">Rp.40.000</p>
                        <img src="https://via.placeholder.com/300x300.png?text=Produk+Kerajinan" alt="Lampu tidur hias" class="w-full h-48 object-contain mb-4">
                        <button class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-6 rounded-md transition duration-300 ease-in-out transform hover:scale-105">
                            Tambah ke keranjang
                        </button>
                    </div>
                </div>
            @endfor
        </div>
        <div class="text-center mt-10">
            <a href="#" class="text-orange-600 font-semibold text-lg hover:text-orange-700">
                Lihat barang lainnya >>>
            </a>
        </div>
    </div>
</div>
