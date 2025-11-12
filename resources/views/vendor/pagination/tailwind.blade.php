@if ($paginator->hasPages())
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <nav class="flex justify-center mb-4 sm:mb-0 sm:order-1" role="navigation" aria-label="{!! __('Pagination Navigation') !!}">
            {{-- Previous Page Link --}}
            <div class="mr-2">
                @if ($paginator->onFirstPage())
                    <span class="inline-flex items-center justify-center rounded-lg leading-5 px-2.5 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700/60 text-gray-300 dark:text-gray-600">
                        <span class="sr-only">{!! __('pagination.previous') !!}</span><wbr />
                        <svg class="fill-current" width="16" height="16" viewBox="0 0 16 16">
                            <path d="M9.4 13.4l1.4-1.4-4-4 4-4-1.4-1.4L4 8z" />
                        </svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center justify-center rounded-lg leading-5 px-2.5 py-2 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-900 border border-gray-200 dark:border-gray-700/60 text-violet-500 shadow-xs">
                        <span class="sr-only">{!! __('pagination.previous') !!}</span><wbr />
                        <svg class="fill-current" width="16" height="16" viewBox="0 0 16 16">
                            <path d="M9.4 13.4l1.4-1.4-4-4 4-4-1.4-1.4L4 8z" />
                        </svg>
                    </a>
                @endif
            </div>

            {{-- Pagination Elements with limited range for better UX --}}
            <ul class="inline-flex text-sm font-medium -space-x-px rounded-lg shadow-xs">
                @php
                    $currentPage = $paginator->currentPage();
                    $lastPage = $paginator->lastPage();
                    $onEachSide = 2; // Show 2 pages on each side of current page
                    
                    $start = max(1, $currentPage - $onEachSide);
                    $end = min($lastPage, $currentPage + $onEachSide);
                    
                    $hasStartEllipsis = $start > 1;
                    $hasEndEllipsis = $end < $lastPage;
                @endphp
                
                {{-- First page (if needed) --}}
                @if($start > 1)
                    <li>
                        <a href="{{ $paginator->url(1) }}" class="inline-flex items-center justify-center leading-5 px-3.5 py-2 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-900 border border-gray-200 dark:border-gray-700/60 text-gray-600 dark:text-gray-300 rounded-l-lg">{{ 1 }}</a>
                    </li>
                @endif
                
                {{-- Start ellipsis (if needed) --}}
                @if($hasStartEllipsis && $start > 2)
                    <li aria-disabled="true">
                        <span class="inline-flex items-center justify-center leading-5 px-3.5 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700/60 text-gray-400 dark:text-gray-500">...</span>
                    </li>
                @endif
                
                {{-- Page links --}}
                @for($page = $start; $page <= $end; $page++)
                    @if($page == $currentPage)
                        <li aria-current="page">
                            <span class="inline-flex items-center justify-center leading-5 px-3.5 py-2 bg-violet-600 text-white border border-violet-600">{{ $page }}</span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $paginator->url($page) }}" class="inline-flex items-center justify-center leading-5 px-3.5 py-2 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-900 border border-gray-200 dark:border-gray-700/60 text-gray-600 dark:text-gray-300 @if($page === $start && $start === 1){{ 'rounded-l-lg' }}@elseif($page === $end && $end === $lastPage && !$hasEndEllipsis){{ 'rounded-r-lg' }}@endif">{{ $page }}</a>
                        </li>
                    @endif
                @endfor
                
                {{-- End ellipsis (if needed) --}}
                @if($hasEndEllipsis && $end < $lastPage - 1)
                    <li aria-disabled="true">
                        <span class="inline-flex items-center justify-center leading-5 px-3.5 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700/60 text-gray-400 dark:text-gray-500">...</span>
                    </li>
                @endif
                
                {{-- Last page (if needed) --}}
                @if($hasEndEllipsis)
                    <li>
                        <a href="{{ $paginator->url($lastPage) }}" class="inline-flex items-center justify-center leading-5 px-3.5 py-2 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-900 border border-gray-200 dark:border-gray-700/60 text-gray-600 dark:text-gray-300 rounded-r-lg">{{ $lastPage }}</a>
                    </li>
                @endif
            </ul>

            {{-- Next Page Link --}}
            <div class="ml-2">
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center justify-center rounded-lg leading-5 px-2.5 py-2 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-900 border border-gray-200 dark:border-gray-700/60 text-violet-500 shadow-xs">
                        <span class="sr-only">{!! __('pagination.next') !!}</span><wbr />
                        <svg class="fill-current" width="16" height="16" viewBox="0 0 16 16">
                            <path d="M6.6 13.4L5.2 12l4-4-4-4 1.4-1.4L12 8z" />
                        </svg>
                    </a>
                @else
                    <span class="inline-flex items-center justify-center rounded-lg leading-5 px-2.5 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700/60 text-gray-300 dark:text-gray-600">
                        <span class="sr-only">{!! __('pagination.next') !!}</span><wbr />
                        <svg class="fill-current" width="16" height="16" viewBox="0 0 16 16">
                            <path d="M6.6 13.4L5.2 12l4-4-4-4 1.4-1.4L12 8z" />
                        </svg>
                    </span>
                @endif
            </div>
        </nav>

        {{-- Results text is now removed to prevent duplication with component-specific text --}}
    </div>
@endif