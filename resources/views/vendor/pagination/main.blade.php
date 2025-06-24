@if ($paginator->hasPages())
    <nav class="flex justify-center mt-6" role="navigation" aria-label="Pagination Navigation">
        <ul class="flex flex-wrap justify-center gap-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span
                        class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 text-gray-400 cursor-not-allowed select-none">&lsaquo;</span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}"
                        class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 text-[#3b5d85] hover:bg-gray-100 transition"
                        rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                </li>
            @endif

            {{-- Dynamic Page Numbers --}}
            @php
                $total = $paginator->lastPage();
                $current = $paginator->currentPage();
                $start = max(1, $current - 1);
                $end = min($total, $current + 1);
            @endphp

            {{-- Page 1 and "…" if needed --}}
            @if ($current > 2)
                <li>
                    <a href="{{ $paginator->url(1) }}"
                        class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 text-[#3b5d85] hover:bg-gray-100 transition">1</a>
                </li>
                @if ($current > 3)
                    <li>
                        <span
                            class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 text-gray-500 select-none">…</span>
                    </li>
                @endif
            @endif

            {{-- Middle range --}}
            @for ($i = $start; $i <= $end; $i++)
                @if ($i == $current)
                    <li>
                        <span
                            class="w-10 h-10 flex items-center justify-center rounded-lg bg-[#3b5d85] text-white font-semibold">{{ $i }}</span>
                    </li>
                @else
                    <li>
                        <a href="{{ $paginator->url($i) }}"
                            class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 text-[#3b5d85] hover:bg-gray-100 transition">{{ $i }}</a>
                    </li>
                @endif
            @endfor

            {{-- "…" and last page --}}
            @if ($current < $total - 1)
                @if ($current < $total - 2)
                    <li>
                        <span
                            class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 text-gray-500 select-none">…</span>
                    </li>
                @endif
                <li>
                    <a href="{{ $paginator->url($total) }}"
                        class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 text-[#3b5d85] hover:bg-gray-100 transition">{{ $total }}</a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}"
                        class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 text-[#3b5d85] hover:bg-gray-100 transition"
                        rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                </li>
            @else
                <li>
                    <span
                        class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 text-gray-400 cursor-not-allowed select-none">&rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
