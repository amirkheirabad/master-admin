@if ($paginator->hasPages())
    <style>
        .custom-pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin: 30px 0;
            padding: 0;
            list-style: none;
        }

        .custom-pagination .page-item {
            display: inline-block;
        }

        .custom-pagination .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 0 12px;
            border-radius: 8px;
            background: #fff;
            color: #4a5568;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            border: 2px solid #e2e8f0;
        }

        /* فلش‌ها */
        .custom-pagination .page-item:first-child .page-link,
        .custom-pagination .page-item:last-child .page-link {
            font-size: 18px;
            font-weight: bold;
            padding: 0 16px;
        }

        /* هاور برای دکمه‌های غیرفعال و غیراکتیو - متناسب با #133c6d */
        .custom-pagination .page-item:not(.disabled):not(.active) .page-link:hover {
            background: #e8edf5;
            border-color: #133c6d;
            color: #133c6d;
        }

        /* صفحه فعال با رنگ #133c6d */
        .custom-pagination .page-item.active .page-link {
            background: #133c6d;
            color: white;
            border-color: #133c6d;
        }

        /* حالت disabled */
        .custom-pagination .page-item.disabled .page-link {
            background: #f1f5f9;
            color: #94a3b8;
            cursor: not-allowed;
            border-color: #e2e8f0;
        }

        /* سه نقطه */
        .custom-pagination .page-item.disabled .page-link {
            background: transparent;
            border: none;
        }
    </style>

    <nav>
        <ul class="custom-pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">&laquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">&raquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
