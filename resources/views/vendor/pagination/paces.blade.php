@if ($paginator->hasPages())
  <nav class="pagination-wrapper" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; padding:1.25rem 0 0.5rem 0;">
    <div style="font-size:0.85rem; color:var(--paces-text-muted); font-weight:400;">
      Showing <strong>{{ $paginator->firstItem() }}</strong> to <strong>{{ $paginator->lastItem() }}</strong> of <strong>{{ $paginator->total() }}</strong> results
    </div>
    
    <div style="display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
      {{-- Previous Page Link --}}
      @if ($paginator->onFirstPage())
        <span class="btn-paces btn-paces-outline btn-paces-sm" style="opacity:0.5; cursor:not-allowed; padding:6px 12px; font-size:0.82rem;">« Previous</span>
      @else
        <a href="{{ $paginator->previousPageUrl() }}" class="btn-paces btn-paces-outline btn-paces-sm" style="padding:6px 12px; font-size:0.82rem; text-decoration:none;">« Previous</a>
      @endif

      {{-- Pagination Elements --}}
      @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
          <span style="padding:4px 8px; color:var(--paces-text-muted); font-size:0.85rem;">{{ $element }}</span>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
          @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
              <span class="btn-paces btn-paces-primary btn-paces-sm" style="min-width:32px; text-align:center; padding:6px 10px; font-size:0.82rem; font-weight:500;">{{ $page }}</span>
            @else
              <a href="{{ $url }}" class="btn-paces btn-paces-outline btn-paces-sm" style="min-width:32px; text-align:center; padding:6px 10px; font-size:0.82rem; text-decoration:none;">{{ $page }}</a>
            @endif
          @endforeach
        @endif
      @endforeach

      {{-- Next Page Link --}}
      @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="btn-paces btn-paces-outline btn-paces-sm" style="padding:6px 12px; font-size:0.82rem; text-decoration:none;">Next »</a>
      @else
        <span class="btn-paces btn-paces-outline btn-paces-sm" style="opacity:0.5; cursor:not-allowed; padding:6px 12px; font-size:0.82rem;">Next »</span>
      @endif
    </div>
  </nav>
@else
  <div style="padding:1rem 0; font-size:0.85rem; color:var(--paces-text-muted); font-weight:400;">
    Showing all <strong>{{ $paginator->total() }}</strong> results
  </div>
@endif
