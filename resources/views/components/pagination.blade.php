@if ($paginator->hasPages())
    <nav class="pagination-nav" role="navigation" aria-label="Pagination">
        @if ($paginator->onFirstPage())
            <span class="btn btn-small disabled" aria-disabled="true">Previous</span>
        @else
            <a class="btn btn-small" href="{{ $paginator->previousPageUrl() }}" rel="prev">Previous</a>
        @endif

        <div class="pagination-pages">
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="pagination-gap">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="btn btn-primary btn-small" aria-current="page">{{ $page }}</span>
                        @else
                            <a class="btn btn-small" href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        @if ($paginator->hasMorePages())
            <a class="btn btn-small" href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a>
        @else
            <span class="btn btn-small disabled" aria-disabled="true">Next</span>
        @endif
    </nav>
@endif
