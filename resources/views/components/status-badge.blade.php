@props(['status'])

@php
    $map = [
        'pending'   => ['label' => 'Menunggu Pembayaran', 'color' => 'bg-warning text-dark'],
        'paid'      => ['label' => 'Pembayaran Berhasil', 'color' => 'bg-success'],
        'verified'  => ['label' => 'Menunggu Konfirmasi', 'color' => 'bg-secondary'],
        'sent'      => ['label' => 'Sedang Dikirim',      'color' => 'bg-info text-dark'],
        'done'      => ['label' => 'Selesai',             'color' => 'bg-primary'],
        'cancelled' => ['label' => 'Dibatalkan',          'color' => 'bg-danger'],
        'waiting'   => ['label' => 'Menunggu',            'color' => 'bg-warning text-dark'],
    ];

    $label = $map[$status]['label'] ?? 'Status Tidak Dikenal';
    $color = $map[$status]['color'] ?? 'bg-dark';
@endphp

<span class="badge {{ $color }} px-3 py-2 rounded-pill fw-semibold">
    {{ $label }}
</span>
