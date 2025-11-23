@props([
    'paginator' => null,
    'title' => '',
])

@php
    if ($paginator) {
        $totalPages = $paginator->lastPage();
        $currentPage = $paginator->currentPage();
        $start = max(1, $currentPage - 2);
        $end = min($totalPages, $currentPage + 2);
    } else {
        $totalPages = $currentPage = $start = $end = 0;
    }

    // ambil query string kecuali page
    $queryParams = request()->except('page');
    $queryString = count($queryParams) ? '&' . http_build_query($queryParams) : '';
@endphp

<div class="card mb-4">

    {{-- Header + Pagination (atas) --}}
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">{{ $title }}</h3>

        @if ($paginator)
            <ul class="pagination p-2 pagination-sm mb-0">
                {{-- Prev --}}
                <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $paginator->url(1) }}{{ $queryString }}">&laquo;</a>
                </li>

                {{-- Numbers --}}
                @for ($i = $start; $i <= $end; $i++)
                    <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                        <a class="page-link" href="{{ $paginator->url($i) }}{{ $queryString }}">
                            {{ $i }}
                        </a>
                    </li>
                @endfor

                {{-- Next --}}
                <li class="page-item {{ !$paginator->hasMorePages() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $paginator->url($totalPages) }}{{ $queryString }}">&raquo;</a>
                </li>
            </ul>
        @endif
    </div>

    {{-- Table --}}
    <div class="card-body p-0">
        <div class="table-responsive" style="overflow-y: visible !important;">
            <table class="table mb-0">
                <thead>
                    <tr>{{ $header }}</tr>
                </thead>
                <tbody>
                    {{ $slot }}
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination bawah --}}
    @if ($paginator)
        <div class="card-footer clearfix">
            <ul class="pagination pagination-sm float-end mb-0">
                <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $paginator->url(1) }}{{ $queryString }}">&laquo;</a>
                </li>

                @for ($i = $start; $i <= $end; $i++)
                    <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                        <a class="page-link" href="{{ $paginator->url($i) }}{{ $queryString }}">
                            {{ $i }}
                        </a>
                    </li>
                @endfor

                <li class="page-item {{ !$paginator->hasMorePages() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $paginator->url($totalPages) }}{{ $queryString }}">&raquo;</a>
                </li>
            </ul>
        </div>
    @endif

</div>
