<x-layouts.admin.main>
    <x-slot:title>
        {{ $title ?? 'Judul Default' }}
    </x-slot:title>
    <div class="container-fluid py-4">

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0">📦 Daftar Transaksi</h4>

                    {{-- Nanti bisa dipakai buat filter jika perlu --}}
                    <form action="" method="GET" class="d-flex gap-2">
                        <input type="text" class="form-control form-control-sm"
                            name="search" placeholder="Cari kode transaksi...">
                        <button class="btn btn-sm btn-dark">Cari</button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>#</th>
                                <th>Kode</th>
                                <th>Pembeli</th>
                                <th>Total Item</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="text-center">
                            @forelse ($transactions as $trx)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td class="fw-semibold">
                                        {{ $trx->trx_code }}
                                    </td>

                                    <td>{{ $trx->buyer->name ?? '-' }}</td>

                                    <td>{{ $trx->trx_total_items }}</td>

                                    <td>Rp{{ number_format($trx->trx_total_price, 0, ',', '.') }}</td>

                                    <td>
                                        @if ($trx->trx_status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif ($trx->trx_status === 'success')
                                            <span class="badge bg-success">Sukses</span>
                                        @elseif ($trx->trx_status === 'failed')
                                            <span class="badge bg-danger">Gagal</span>
                                        @endif
                                    </td>

                                    <td>{{ $trx->trx_created_at }}</td>

                                    <td>
                                        <a href="{{ route('admin.transactions.show', $trx->trx_id) }}"
                                        class="btn btn-sm btn-primary">
                                            Detail
                                        </a>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="8" class="text-muted py-4">
                                        Belum ada transaksi...
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-layouts.admin.main>
