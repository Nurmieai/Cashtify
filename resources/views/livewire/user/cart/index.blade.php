<x-layouts.crs>
    <x-slot:title>Keranjang Belanja</x-slot:title>

    <div class="container py-5" style="max-width: 1100px;">
        <div class="row">

            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4 p-4 mb-4" style="margin-top: 120px;">

                    @foreach ($cart->items as $item)
                        <div class="border rounded-4 p-3 mb-3">

                            <div class="d-flex align-items-center mb-3">
                                <input type="checkbox" class="form-check-input me-2" checked>
                                <h6 class="fw-bold mb-0">{{ $item->product->seller->name ?? 'Penjual' }}</h6>
                            </div>

                            <div class="d-flex align-items-start gap-3">
                                <img src="{{ $item->product->prd_card_url ? asset($item->product->prd_card_url) : asset('assets/images/logo.svg') }}"
                                     width="80" height="80" class="rounded-3" style="object-fit: contain;">

                                <div class="flex-grow-1">
                                    <h5 class="fw-semibold">{{ $item->product->prd_name }}</h5>
                                    <span class="text-danger fw-bold">
                                        Rp {{ number_format($item->crs_item_price, 0, ',', '.') }}
                                    </span>
                                </div>

                                <div class="text-end">
                                    <form action="{{ route('cart.remove', $item->crs_item_id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger mb-3">Hapus</button>
                                    </form>

                                    <form action="{{ route('cart.update', $item->crs_item_id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="input-group input-group-sm" style="width: 130px;">
                                            <button class="btn btn-outline-secondary"
                                                type="submit" name="quantity"
                                                value="{{ $item->crs_item_quantity - 1 }}">-</button>

                                            <input type="text" class="form-control text-center"
                                                value="{{ $item->crs_item_quantity }}" readonly>

                                            <button class="btn btn-outline-secondary"
                                                type="submit" name="quantity"
                                                value="{{ $item->crs_item_quantity + 1 }}">+</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    @endforeach

                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-4 p-4 position-sticky"
                     style="margin-top: 120px;">
                    <h5 class="fw-bold mb-2">Total Pembelian</h5>
                    <div class="text-muted mb-3">({{ $cart->crs_total_items }} Produk)</div>

                    <h3 class="fw-bold text-danger mb-4">
                        Rp {{ number_format($cart->crs_total_price, 0, ',', '.') }}
                    </h3>

                        <a href="{{ route('checkout.cart') }}"
                        class="btn btn-danger w-100 py-2 rounded-3 fw-semibold">
                            Beli
                        </a>
                </div>
            </div>

        </div>
    </div>

</x-layouts.crs>
