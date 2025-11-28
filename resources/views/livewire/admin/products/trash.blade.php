<x-layouts.admin.main>
    <x-slot:title>{{ $title }}</x-slot:title>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">Produk Terhapus</h3>
            <a href="{{ route('products.adminIndex') }}" class="btn btn-secondary">Kembali</a>
        </div>

        <div class="card-body">
            @if ($products->count() == 0)
                <p class="text-muted">Tidak ada produk terhapus.</p>
            @else
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th>Dihapus Pada</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($products as $p)
                            <tr>
                                <td>{{ $p->prd_name }}</td>
                                <td>Rp {{ number_format($p->prd_price, 0, ',', '.') }}</td>
                                <td>{{ $p->usr_deleted_at }}</td>

                                <td class="d-flex gap-2">

                                    <form action="{{ route('products.restore', $p->prd_id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-success btn-sm">Kembalikan Ke Daftar Produk</button>
                                    </form>

                                    <form action="{{ route('products.forceDelete', $p->prd_id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Hapus permanen?')">

                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Hapus Permanen</button>
                                    </form>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $products->links() }}
            @endif
        </div>
    </div>
</x-layouts.admin.main>
