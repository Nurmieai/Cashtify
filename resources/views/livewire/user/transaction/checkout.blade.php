<x-layouts.tst>
    <x-slot name="title">Checkout</x-slot>
    @section('title', 'Checkout')

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <section class="py-5" style="margin-top: 20px; scroll-margin-top: 80px;">
        <div class="container" style="max-width: 800px;">

            <!-- HEADER -->
            <div class="mb-4 text-center">
                <h3 class="fw-bold">Checkout</h3>
                <p class="text-muted">Periksa kembali pesanan Anda sebelum melanjutkan pembayaran.</p>
            </div>

            <!-- CARD WRAPPER -->
            <div class="card shadow-sm border-0 rounded-4 p-4">

                <!-- FORM CHECKOUT -->
                <form id="checkoutForm" action="{{ route('checkout.product.store', $product->prd_id) }}" method="POST">
                    @csrf

                    <!-- PRODUK -->
                    <h5 class="fw-semibold mb-3">Detail Produk</h5>
                    <div class="d-flex align-items-center mb-4">
                        <img src="{{ $product->prd_card_url ? asset($product->prd_card_url) : asset('assets/images/logo.svg') }}"
                             class="rounded-3 me-3"
                             style="width: 100px; height: 100px; object-fit: contain;">

                        <div>
                            <h6 class="fw-bold mb-1">{{ $product->prd_name }}</h6>
                            <p class="text-primary fw-semibold mb-0">
                                Rp {{ number_format($product->prd_price, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <!-- JUMLAH -->
                    <div class="mb-4">
                        <label class="fw-semibold mb-2">Jumlah</label>
                        <input type="number"
                               name="quantity"
                               min="1"
                               value="1"
                               class="form-control rounded-3 shadow-sm"
                               style="max-width: 150px;">
                    </div>

                    <h5 class="fw-semibold mb-3">Lokasi Pengiriman</h5>
                    <p class="text-muted">Pilih titik lokasi rumah kamu di peta di bawah ini.</p>

                    <div class="mb-3 p-3 bg-light rounded-3 shadow-sm">
                        <strong>Lokasi Terpilih:</strong><br>
                        <span id="loc-lat">Latitude: -</span><br>
                        <span id="loc-lng">Longitude: -</span><br>
                        <span id="loc-city" class="text-primary fw-semibold"></span>
                    </div>

                    <div id="map" style="height: 300px; border-radius: 12px; margin-bottom: 20px;"></div>

                    <!-- HIDDEN INPUT UNTUK LAT & LNG -->
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">

                    <!-- ALAMAT OPSIONAL -->
                    <div class="mb-4">
                        <label class="fw-semibold mb-2">Catatan Alamat (Opsional)</label>
                        <textarea name="address"
                                  class="form-control shadow-sm rounded-3"
                                  rows="3"
                                  placeholder="Contoh: Rumah pagar putih, dekat pos ronda..."></textarea>
                    </div>

                    <!-- PEMBAYARAN -->
                    <h5 class="fw-semibold mb-3">Metode Pembayaran</h5>
                    <input type="hidden" name="payment_method" id="payment_method">

                    <div class="row g-3 mb-4">
                        <div class="col-md-4 d-flex justify-content-center">
                            <div class="card payment-option border-1 shadow-sm p-3 rounded-3 text-center"
                                data-method="bank_transfer"
                                style="cursor: pointer; width:100%; max-width: 250px;">
                                <img src="{{ asset('assets/images/bank.svg') }}" style="height: 40px;">
                                <p class="mt-2 mb-0 fw-semibold">Transfer Bank</p>
                            </div>
                        </div>

                        <div class="col-md-4 d-flex justify-content-center">
                            <div class="card payment-option border-1 shadow-sm p-3 rounded-3 text-center"
                                data-method="dana"
                                style="cursor: pointer; width:100%; max-width: 250px;">
                                <img src="{{ asset('assets/images/dana.svg') }}" style="height: 40px;">
                                <p class="mt-2 mb-0 fw-semibold">DANA</p>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                        class="btn btn-danger w-100 py-3 fw-semibold rounded-3 shadow-sm">
                        Konfirmasi Pembayaran
                    </button>
                </form>

            </div>
        </div>
    </section>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>

        const options = document.querySelectorAll('.payment-option');
        const inputMethod = document.getElementById('payment_method');
        const form = document.getElementById('checkoutForm');

        options.forEach(opt => {
            opt.addEventListener('click', () => {
                options.forEach(o => o.classList.remove('border-primary', 'shadow', 'border-3'));
                opt.classList.add('border-primary', 'shadow', 'border-3');
                inputMethod.value = opt.dataset.method;
            });
        });

        form.addEventListener('submit', (e) => {
            if (!inputMethod.value) {
                e.preventDefault();
                alert('Pilih metode pembayaran terlebih dahulu!');
            }
        });

        const map = L.map('map').setView([-6.2000, 106.8166], 11);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19
        }).addTo(map);

        let marker = L.marker([-6.2000, 106.8166], { draggable: true }).addTo(map);

        updateLatLng(-6.2000, 106.8166);

        function updateLatLng(lat, lng) {
            document.getElementById("latitude").value = lat;
            document.getElementById("longitude").value = lng;

            document.getElementById("loc-lat").innerText = "Latitude: " + lat.toFixed(6);
            document.getElementById("loc-lng").innerText = "Longitude: " + lng.toFixed(6);

            fetchCity(lat, lng);
        }

        function fetchCity(lat, lng) {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(res => res.json())
                .then(data => {
                    if (data.address) {
                        const city =
                            data.address.city ||
                            data.address.town ||
                            data.address.village ||
                            data.address.suburb ||
                            data.address.county ||
                            "-";

                        document.getElementById("loc-city").innerText = "Kota: " + city;
                    }
                })
                .catch(() => {
                    document.getElementById("loc-city").innerText = "";
                });
        }

        marker.on("dragend", function () {
            const pos = marker.getLatLng();
            updateLatLng(pos.lat, pos.lng);
        });

        map.on("click", function (e) {
            marker.setLatLng(e.latlng);
            updateLatLng(e.latlng.lat, e.latlng.lng);
        });
    </script>
</x-layouts.tst>
