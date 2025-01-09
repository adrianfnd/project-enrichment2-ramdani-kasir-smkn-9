@extends('layouts.app')

@section('content')

<div class="container">
    <div class="page-inner">
        <div class="row">
            <div class="col-lg-8 px-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="mb-0">Daftar Produk</h4>
                            </div>
                            <div class="col">
                                <input type="text" id="searchProduct" class="form-control" placeholder="Cari produk...">
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row" id="productList">
                            @foreach($stocks as $stock)
                            <div class="col-md-4 mb-4 product-item">
                                <div class="card h-100 product-card">
                                    <div class="card-body">
                                        <h5 class="card-title text-truncate">{{ $stock->name }}</h5>
                                        <p class="card-text">
                                            <small class="text-muted">{{ $stock->description }}</small>
                                        </p>
                                        <p class="card-text">
                                            <span class="text-primary font-weight-bold">
                                                Rp {{ number_format($stock->price, 0, ',', '.') }}
                                            </span>
                                        </p>
                                        <p class="card-text">
                                            <small class="text-muted">Stok: {{ $stock->quantity }}</small>
                                        </p>
                                        <div class="d-flex align-items-center mt-2">
                                            <input type="number" class="form-control form-control-sm qty-input" 
                                                   min="1" value="1" max="{{ $stock->quantity }}">
                                            <button class="btn btn-primary btn-sm ml-2 add-to-cart"
                                                    data-id="{{ $stock->id }}">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="col-lg-4 px-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Keranjang</h4>
                    </div>
                    <div class="card-body p-0">
                        <div id="cartItems">
                            @include('admin.transaction.cart-items')
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <button class="btn btn-success btn-lg btn-block checkout-btn" 
                                {{ $carts->isEmpty() ? 'disabled' : '' }}>
                            Checkout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
   $(document).ready(function() {
        $('#searchProduct').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('.product-item').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        $('.add-to-cart').click(function() {
            var stockId = $(this).data('id');
            var quantity = $(this).closest('.card-body').find('.qty-input').val();

            $.ajax({
                url: '{{ route("transaksi.addToCart") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    stock_id: stockId,
                    quantity: quantity
                },
                success: function(response) {
                    $('#cartItems').html(response.cartHtml);
                    updateCheckoutButton();
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: xhr.responseJSON.message
                    });
                }
            });
        });

        $(document).on('click', '.remove-from-cart', function() {
            var cartId = $(this).data('id');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Produk akan dihapus dari keranjang",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("transaksi.removeFromCart") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            cart_id: cartId
                        },
                        success: function(response) {
                            $('#cartItems').html(response.cartHtml);
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                            updateCheckoutButton();
                        }
                    });
                }
            });
        });

        $('.checkout-btn').click(function() {
            Swal.fire({
                title: 'Konfirmasi Checkout',
                text: "Apakah Anda yakin ingin melakukan checkout?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, checkout!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("transaksi.checkout") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: xhr.responseJSON.message
                            });
                        }
                    });
                }
            });
        });

        function updateCheckoutButton() {
            if ($('.cart-item').length > 0) {
                $('.checkout-btn').prop('disabled', false);
            } else {
                $('.checkout-btn').prop('disabled', true);
            }
        }
    });
</script>

<style>
    .product-card {
        transition: transform 0.2s;
        cursor: pointer;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .cart-item {
        padding: 1rem;
        border-bottom: 1px solid #eee;
    }
    .cart-item:last-child {
        border-bottom: none;
    }
    .qty-input {
        width: 70px;
    }
</style>
@endsection