<div class="cart-items-container">
    @forelse($carts as $cart)
    <div class="cart-item">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-0">{{ $cart->stock->name }}</h6>
                <small class="text-muted">
                    Rp {{ number_format($cart->stock->price, 0, ',', '.') }} x 
                    <input type="number" 
                           class="form-control form-control-sm d-inline cart-qty-input" 
                           style="width: 60px"
                           value="{{ $cart->quantity }}" 
                           min="1" 
                           max="{{ $cart->stock->quantity }}"
                           disabled>
                </small>
            </div>
            <div class="text-right">
                <div class="mb-1">
                    <strong>
                        Rp {{ number_format($cart->quantity * $cart->stock->price, 0, ',', '.') }}
                    </strong>
                </div>
                <button class="btn btn-sm btn-danger remove-from-cart" data-id="{{ $cart->id }}">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-4">
        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
        <p class="text-muted">Keranjang kosong</p>
    </div>
    @endforelse
</div>

@if($carts->isNotEmpty())
<div class="cart-summary">
    <div class="p-3 bg-light border-top">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Total</h5>
            <h5 class="mb-0">Rp {{ number_format($total, 0, ',', '.') }}</h5>
        </div>
    </div>
</div>
@endif