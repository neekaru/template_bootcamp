<?php

namespace App\Livewire\ProductDetail;

use App\Models\Produk;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('components.layouts.app')]
class ProductReview extends Component
{
    use WithFileUploads;

    public Produk $product;
    public $transactionId;

    #[Rule('required|integer|min:1|max:5')]
    public $rating = 0;

    #[Rule('nullable|string|max:1000')]
    public $review_text = '';

    #[Rule('nullable|array')]
    public $photos = [];

    protected function rules()
    {
        return [
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string|max:1000',
            'photos.*' => 'nullable|image|max:2048'
        ];
    }

    public function mount($productId, $transactionId = null)
    {
        $this->product = Produk::findOrFail($productId);
        $this->transactionId = $transactionId;
    }

    public function setRating($value)
    {
        $this->rating = $value;
    }

    public function getRatingTextProperty()
    {
        switch ($this->rating) {
            case 1: return 'Sangat Buruk';
            case 2: return 'Buruk';
            case 3: return 'Cukup';
            case 4: return 'Baik';
            case 5: return 'Sangat Baik';
            default: return '';
        }
    }

    public function submitReview()
    {
        $this->validate();

        if (!Auth::guard('pembeli')->check()) {
            session()->flash('error', 'Anda harus login untuk memberikan ulasan.');
            return $this->redirectRoute('login');
        }

        $pembeliId = Auth::guard('pembeli')->id();

        $photoPaths = [];
        if (!empty($this->photos)) {
            foreach ($this->photos as $photo) {
                $photoPaths[] = $photo->store('review', 'public');
            }
        }

        Rating::create([
            'pembeli_id' => $pembeliId,
            'produk_id' => $this->product->id,
            'transaction_id' => $this->transactionId,
            'rating' => $this->rating,
            'review' => $this->review_text,
            'foto_review' => json_encode($photoPaths), // Store as JSON string
        ]);

        session()->flash('success', 'Ulasan Anda berhasil dikirim!');
        return $this->redirectRoute('product.detail', ['productId' => $this->product->id]);
    }

    public function cancel()
    {
        return $this->redirectRoute('product.detail', ['productId' => $this->product->id]);
    }

    public function render()
    {
        return view('livewire.product-detail.product-review');
    }
}
