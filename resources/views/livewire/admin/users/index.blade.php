<x-layouts.admin.main>
    <x-slot:title>Daftar Pengguna</x-slot:title>

    <style>
        .user-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 0;
        }

        .search-box input {
            height: 48px;
            border-radius: 10px;
            padding-left: 14px;
        }

        .search-box button {
            border-radius: 10px;
            padding: 0 20px;
        }

        .table {
            border-radius: 12px !important;
            overflow: hidden;
        }

        .table thead {
            background: #f8f9fa;
        }

        .table-hover tbody tr:hover {
            background: #f1f5ff !important;
        }
    </style>

    <x-table_data :paginator="$users" title="Daftar Pengguna">

        <x-slot:header>
            <div class="user-header">

                <form action="{{ route('admin.users') }}" method="GET" class="d-flex search-box flex-grow-1 me-3">
                    <input type="text" name="search" class="form-control me-2"
                           placeholder="Cari nama / email"
                           value="{{ request('search') }}">
                    <button class="btn btn-outline-success btn-lg me-3"><i class="bi bi-search"></i></button>
                </form>

            </div>
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </x-slot:header>

        <tr>
            <td colspan="1">
                <div class="table-responsive mt-3">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Dibuat</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <div>
                                        Role:
                                        <strong>
                                            @if($user->hasRole('Penjual'))
                                                Penjual
                                            @elseif($user->hasRole('Pembeli'))
                                                Pembeli
                                            @else
                                                Tidak Diketahui
                                            @endif
                                        </strong>
                                    </div>
                                </td>
                                    <td>{{ \Carbon\Carbon::parse($user->usr_created_at)->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        Tidak ada pengguna ditemukan…
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>

    </x-table_data>

</x-layouts.admin.main>
