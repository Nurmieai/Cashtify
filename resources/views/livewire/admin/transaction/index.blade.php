<x-layouts.admin.main>
    <style>
    /* Hilangkan scroll horizontal global */
    .card-body,
    .table-responsive {
        overflow: visible !important;
        white-space: normal !important;
    }

    /* Bikin tabel dan card tidak memaksa overflow */
    table {
        width: 100% !important;
    }

    img, .card-img-top {
        max-width: 100% !important;
        height: auto !important;
    }
</style>

    <x-table_data :paginator="$transactions" title="Kelola Transaksi">
        <x-slot:header>
            <th>#</th>
            <th>Invoice</th>
            <th>Pembeli</th>
            <th>Total Item</th>
            <th>Total Harga</th>
            <th>Status</th>
            <th>Tanggal</th>
            <th></th>
        </x-slot:header>

        @forelse ($transactions as $trx)
            <tr class="text-center">
                <td>{{ $loop->iteration }}</td>
                <td class="fw-semibold">{{ $trx->tst_invoice }}</td>
                <td>{{ $trx->buyer->name ?? '-' }}</td>
                <td>{{ $trx->items->count() }}</td>
                <td>Rp{{ number_format($trx->tst_total, 0, ',', '.') }}</td>

                @php
                    $st = [
                        'pending'   => ['Pending', 'warning'],
                        'paid'      => ['Dibayar', 'success'],
                        'verified'  => ['Dikemas', 'primary'],
                        'sent'      => ['Dikirim', 'info'],
                        'done'      => ['Selesai', 'success'],
                        'cancelled' => ['Dibatalkan', 'danger'],
                        'waiting'   => ['Menunggu Konfirmasi', 'secondary'],
                    ];
                @endphp

                <td>
                    <span class="badge bg-{{ $st[$trx->tst_status][1] }}">
                        {{ $st[$trx->tst_status][0] }}
                    </span>
                </td>

                <td>{{ $trx->tst_created_at }}</td>

                <td>
                    <div class="dropdown dropstart">
                        <button class="btn btn-warning btn-sm dropdown-toggle"
                            type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-menu-down"></i>
                        </button>

                        <ul class="dropdown-menu">

                            {{-- Detail --}}
                            <li>
                                <a class="dropdown-item"
                                href="{{ route('admin.transactions.show', $trx->tst_id) }}">
                                Detail
                                </a>
                            </li>

                            @if ($trx->tst_payment_status == 1)
                                <li>
                                    <form action="{{ route('admin.transactions.confirm', $trx->tst_id) }}"
                                        method="POST">
                                        @csrf
                                        <button class="dropdown-item">Konfirmasi Pembayaran</button>
                                    </form>
                                </li>
                            @endif

                            @if ($trx->tst_status == 3)
                                <li>
                                    <form action="{{ route('admin.transactions.ship', $trx->tst_id) }}"
                                        method="POST">
                                        @csrf
                                        <button class="dropdown-item">Kirim Pesanan</button>
                                    </form>
                                </li>
                            @endif

                            {{-- Selesai --}}
                            @if ($trx->tst_status == 4)
                                <li>
                                    <form action="{{ route('admin.transactions.finish', $trx->tst_id) }}"
                                        method="POST">
                                        @csrf
                                        <button class="dropdown-item">Tandai Selesai</button>
                                    </form>
                                </li>
                            @endif

                            {{-- Batalkan --}}
                            @if ($trx->tst_status <= 3)
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('admin.transactions.cancel', $trx->tst_id) }}"
                                        method="POST">
                                        @csrf
                                        <button class="dropdown-item text-danger">
                                            Batalkan Transaksi
                                        </button>
                                    </form>
                                </li>
                            @endif

                        </ul>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="8" class="py-4 text-muted">Belum ada transaksi.</td></tr>
        @endforelse
    </x-table_data>
</x-layouts.admin.main>
