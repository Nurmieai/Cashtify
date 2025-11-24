    @props(['status'])

@php
    $map = [
        1 => ['label' => 'Menunggu Pembayaran',      'color' => 'bg-warning text-dark'],
        2 => ['label' => 'Pembayaran Berhasil',      'color' => 'bg-success'],
        3 => ['label' => 'Menunggu Konfirmasi',      'color' => 'bg-secondary'],
        4 => ['label' => 'Sedang Dikirim',           'color' => 'bg-info text-dark'],
        5 => ['label' => 'Selesai',                  'color' => 'bg-primary'],
        6 => ['label' => 'Dibatalkan',               'color' => 'bg-danger'],
    ];

    $label = $map[$status]['label'] ?? 'Status Tidak Dikenal';
    $color = $map[$status]['color'] ?? 'bg-dark';
@endphp

<span class="badge {{ $color }} px-3 py-2 rounded-pill fw-semibold">
    {{ $label }}
</span>
