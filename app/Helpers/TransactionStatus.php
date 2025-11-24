<?php

if (! function_exists('transactionStatusText')) {
    function transactionStatusText($status)
    {
        return [
            'pending'   => 'Menunggu Pembayaran',
            'paid'      => 'Pembayaran Berhasil',
            'verified'  => 'Sudah Diverifikasi Penjual',
            'sent'      => 'Pesanan Sedang Dikirim',
            'done'      => 'Transaksi Selesai',
            'cancelled' => 'Dibatalkan',
        ][$status] ?? 'Status Tidak Dikenal';
    }
}

if (! function_exists('transactionStatusColor')) {
    function transactionStatusColor($status)
    {
        return [
            'pending'   => 'warning',
            'paid'      => 'info',
            'verified'  => 'primary',
            'sent'      => 'secondary',
            'done'      => 'success',
            'cancelled' => 'danger',
        ][$status] ?? 'dark';
    }
}
