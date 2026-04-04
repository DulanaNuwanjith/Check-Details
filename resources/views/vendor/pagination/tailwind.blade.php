@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}">

        <!-- MOBILE -->
        <div class="flex gap-2 items-center justify-between sm:hidden">

            @if ($paginator->onFirstPage())
                <span class="px-4 py-2 text-sm text-gray-400 bg-gray-100 border rounded-lg cursor-not-allowed">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="px-4 py-2 text-sm text-gray-700 bg-white border rounded-lg hover:bg-gray-100 transition">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="px-4 py-2 text-sm text-gray-700 bg-white border rounded-lg hover:bg-gray-100 transition">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="px-4 py-2 text-sm text-gray-400 bg-gray-100 border rounded-lg cursor-not-allowed">
                    {!! __('pagination.next') !!}
                </span>
            @endif

        </div>

        <!-- DESKTOP -->
        <div class="hidden sm:flex sm:items-center sm:justify-between mt-4">

            <!-- RESULTS INFO -->
            <div>
                <p class="text-sm text-gray-600">
                    Showing
                    @if ($paginator->firstItem())
                        <span class="font-semibold">{{ $paginator->firstItem() }}</span>
                        to
                        <span class="font-semibold">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    of
                    <span class="font-semibold">{{ $paginator->total() }}</span>
                    results
                </p>
            </div>

            <!-- PAGINATION -->
            <div>
                <span class="inline-flex gap-2">

                    <!-- PREVIOUS -->
                    @if ($paginator->onFirstPage())
                        <span class="px-2 py-2 text-gray-400 bg-gray-100 border rounded-lg cursor-not-allowed">
                            ‹
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}"
                           class="px-2 py-2 text-gray-600 bg-white border rounded-lg hover:bg-gray-100 transition">
                            ‹
                        </a>
                    @endif

                    <!-- PAGES -->
                    @foreach ($elements as $element)

                        <!-- DOTS -->
                        @if (is_string($element))
                            <span class="px-3 py-2 text-gray-400">{{ $element }}</span>
                        @endif

                        <!-- LINKS -->
                        @if (is_array($element))
                            @foreach ($element as $page => $url)

                                @if ($page == $paginator->currentPage())
                                    <!-- ACTIVE -->
                                    <span class="px-4 py-2 text-white bg-green-600 border border-green-600 rounded-lg shadow-md font-semibold">
                                        {{ $page }}
                                    </span>
                                @else
                                    <!-- NORMAL -->
                                    <a href="{{ $url }}"
                                       class="px-4 py-2 text-gray-700 bg-white border rounded-lg hover:bg-gray-100 hover:text-gray-900 transition">
                                        {{ $page }}
                                    </a>
                                @endif

                            @endforeach
                        @endif

                    @endforeach

                    <!-- NEXT -->
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}"
                           class="px-2 py-2 text-gray-600 bg-white border rounded-lg hover:bg-gray-100 transition">
                            ›
                        </a>
                    @else
                        <span class="px-2 py-2 text-gray-400 bg-gray-100 border rounded-lg cursor-not-allowed">
                            ›
                        </span>
                    @endif

                </span>
            </div>
        </div>
    </nav>
@endif
