<x-layouts.admin.main>
    <x-slot name="title">Aksi Transaksi - {{ $transaction->tst_invoice }}</x-slot>

    <div class="container py-4">

        <h3 class="fw-bold mb-3">Aksi Transaksi</h3>
        <p class="text-muted mb-4">Transaksi: <strong>{{ $transaction->tst_invoice }}</strong></p>

        {{-- ===================== STATUS ===================== --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Status Saat Ini</h5>

                <div class="mb-2">
                    <span class="text-muted">Status Pembayaran:</span>
                    <span class="badge bg-{{ $pay[$transaction->tst_payment_status][1] }}">
                        {{ $pay[$transaction->tst_payment_status][0] }}
                    </span>
                </div>

                <div class="mb-2">
                    <span class="text-muted">Status Transaksi:</span>
                    <span class="badge bg-{{ $st[$transaction->tst_status][1] }}">
                        {{ $st[$transaction->tst_status][0] }}
                    </span>
                </div>

                <div class="mt-3 mb-1">
                    <span class="text-muted">Total Tagihan:</span>
                    <span class="fw-semibold">Rp{{ number_format($transaction->tst_total, 0, ',', '.') }}</span>
                </div>

                <div>
                    <span class="text-muted">Total Dibayar:</span>
                    <span class="fw-semibold">Rp{{ number_format($transaction->tst_payment_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- ===================== AKSI ===================== --}}
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Aksi Tersedia</h5>

                {{-- ===================== 1. KONFIRMASI PEMBAYARAN ===================== --}}
                @if ($transaction->tst_payment_status == 1)
                    @if ($transaction->tst_payment_amount >= $transaction->tst_total)
                        <form action="{{ route('admin.transactions.confirm', $transaction->tst_id) }}" method="POST">
                            @csrf
                            <button class="btn btn-success w-100 mb-3">
                                Konfirmasi Pembayaran
                            </button>
                        </form>
                    @else
                        <button class="btn btn-secondary w-100 mb-3" disabled>
                            Pembayaran Belum Sesuai
                        </button>

                        <p class="text-danger small">
                            *Pembeli belum membayar penuh.
                            Dibayar: {{ number_format($transaction->tst_payment_amount, 0, ',', '.') }}
                            Wajib: {{ number_format($transaction->tst_total, 0, ',', '.') }}
                        </p>
                    @endif
                @endif

                {{-- ===================== 2. DIKEMAS ===================== --}}
                @if ($transaction->tst_status == 2)
                    <form action="{{ route('admin.transactions.pack', $transaction->tst_id) }}" method="POST">
                        @csrf
                        <button class="btn btn-primary w-100 mb-3">
                            Tandai Dikemas
                        </button>
                    </form>
                @endif

                {{-- ===================== 3. KIRIM ===================== --}}
                @if ($transaction->tst_status == 3)
                    <form action="{{ route('admin.transactions.ship', $transaction->tst_id) }}" method="POST">
                        @csrf
                        <button class="btn btn-info w-100 mb-3">
                            Kirim Pesanan
                        </button>
                    </form>
                @endif

                {{-- ===================== 4. SELESAI ===================== --}}
                @if ($transaction->tst_status == 4)
                    <form action="{{ route('admin.transactions.finish', $transaction->tst_id) }}" method="POST">
                        @csrf
                        <button class="btn btn-success w-100 mb-3">
                            Tandai Selesai
                        </button>
                    </form>
                @endif

                {{-- ===================== 5. BATALKAN ===================== --}}
                @if ($transaction->tst_status <= 3)
                    <form action="{{ route('admin.transactions.cancel', $transaction->tst_id) }}" method="POST">
                        @csrf
                        <button class="btn btn-danger w-100">
                            Batalkan Transaksi
                        </button>
                    </form>
                @endif

                <div class="mt-4 text-center">
                    <a href="{{ route('admin.transactions.show', $transaction->tst_id) }}" class="text-decoration-none">
                        Kembali ke detail
                    </a>
                </div>
            </div>
        </div>

    </div>
</x-layouts.admin.main>
