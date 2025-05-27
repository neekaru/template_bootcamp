<x-layouts.app>
    <div class="container mx-auto px-4 py-8">
        <div class="bg-base-100 shadow-xl rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-4">Pembeli Dashboard</h1>
            
            <div class="bg-base-200 p-4 rounded-lg mb-6">
                <h2 class="text-xl font-semibold mb-2">Welcome, {{ auth('pembeli')->user()->username }}!</h2>
                <p class="text-gray-600">Email: {{ auth('pembeli')->user()->email }}</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-base-200 p-4 rounded-lg">
                    <h3 class="font-semibold mb-2">My Orders</h3>
                    <p>View and manage your orders</p>
                    <button class="btn btn-primary btn-sm mt-2">View Orders</button>
                </div>
                
                <div class="bg-base-200 p-4 rounded-lg">
                    <h3 class="font-semibold mb-2">My Profile</h3>
                    <p>Update your profile information</p>
                    <button class="btn btn-primary btn-sm mt-2">Edit Profile</button>
                </div>
                
                <div class="bg-base-200 p-4 rounded-lg">
                    <h3 class="font-semibold mb-2">Wishlist</h3>
                    <p>Products you've saved for later</p>
                    <button class="btn btn-primary btn-sm mt-2">View Wishlist</button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>