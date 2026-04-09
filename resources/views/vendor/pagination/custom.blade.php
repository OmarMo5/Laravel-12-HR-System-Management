@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span
                    class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-md dark:bg-zink-600 dark:border-zink-500 dark:text-zink-200">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-zink-600 dark:border-zink-500 dark:text-zink-200 dark:hover:bg-zink-500">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-zink-600 dark:border-zink-500 dark:text-zink-200 dark:hover:bg-zink-500">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span
                    class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-md dark:bg-zink-600 dark:border-zink-500 dark:text-zink-200">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            {{-- <div>
                <p class="text-sm text-gray-700 dark:text-zink-200">
                    @if ($paginator->total() > 0)
                        @if (app()->getLocale() == 'ar')
                            عرض {{ $paginator->firstItem() }} إلى {{ $paginator->lastItem() }} من إجمالي
                            {{ $paginator->total() }} نتائج
                        @else
                            Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of
                            {{ $paginator->total() }} results
                        @endif
                    @else
                        @if (app()->getLocale() == 'ar')
                            لا توجد نتائج
                        @else
                            No results found
                        @endif
                    @endif
                </p>
            </div> --}}

            <div>
                <span class="relative z-0 inline-flex rounded-md shadow-sm">
                    {{-- Previous (السابق) --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span
                                class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-l-md dark:bg-zink-600 dark:border-zink-500 dark:text-zink-200"
                                aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                            class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50 dark:bg-zink-600 dark:border-zink-500 dark:text-zink-200 dark:hover:bg-zink-500"
                            aria-label="{{ __('pagination.previous') }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    {{-- السهم الأيسر (أقل من السابق) --}}
                    @if ($paginator->currentPage() > 2)
                        <a href="{{ $paginator->url($paginator->currentPage() - 2) }}"
                            class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 dark:bg-zink-600 dark:border-zink-500 dark:text-zink-200 dark:hover:bg-zink-500">
                            {{ $paginator->currentPage() - 2 }}
                        </a>
                    @endif

                    {{-- السهم الأيسر (أقل بواحد) --}}
                    @if ($paginator->currentPage() > 1)
                        <a href="{{ $paginator->url($paginator->currentPage() - 1) }}"
                            class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 dark:bg-zink-600 dark:border-zink-500 dark:text-zink-200 dark:hover:bg-zink-500">
                            {{ $paginator->currentPage() - 1 }}
                        </a>
                    @endif

                    {{-- الصفحة الحالية --}}
                    <span aria-current="page">
                        <span
                            class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-white bg-custom-500 border border-custom-500 cursor-default">
                            {{ $paginator->currentPage() }}
                        </span>
                    </span>

                    {{-- السهم الأيمن (أكثر بواحد) --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->url($paginator->currentPage() + 1) }}"
                            class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 dark:bg-zink-600 dark:border-zink-500 dark:text-zink-200 dark:hover:bg-zink-500">
                            {{ $paginator->currentPage() + 1 }}
                        </a>
                    @endif

                    {{-- السهم الأيمن (أكثر باثنين) --}}
                    @if ($paginator->currentPage() + 2 <= $paginator->lastPage())
                        <a href="{{ $paginator->url($paginator->currentPage() + 2) }}"
                            class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 dark:bg-zink-600 dark:border-zink-500 dark:text-zink-200 dark:hover:bg-zink-500">
                            {{ $paginator->currentPage() + 2 }}
                        </a>
                    @endif

                    {{-- Next (التالي) --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                            class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50 dark:bg-zink-600 dark:border-zink-500 dark:text-zink-200 dark:hover:bg-zink-500"
                            aria-label="{{ __('pagination.next') }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span
                                class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-r-md dark:bg-zink-600 dark:border-zink-500 dark:text-zink-200"
                                aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
