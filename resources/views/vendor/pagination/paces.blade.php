@if ($paginator->hasPages())
  <nav class="pagination-wrapper" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; padding:1.25rem 0 0.5rem 0;" aria-label="Pagination Navigation">
    <div style="font-size:0.84rem; color:var(--paces-text-muted, #64748b); font-weight:400;">
      Showing <span style="font-weight:500; color:var(--paces-text-dark, #1e293b);">{{ $paginator->firstItem() }}</span> to <span style="font-weight:500; color:var(--paces-text-dark, #1e293b);">{{ $paginator->lastItem() }}</span> of <span style="font-weight:500; color:var(--paces-text-dark, #1e293b);">{{ $paginator->total() }}</span> results
    </div>
    
    <div style="display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
      {{-- Previous Page Link --}}
      @if ($paginator->onFirstPage())
        <span class="btn-paces btn-paces-outline btn-paces-sm" style="opacity:0.45; cursor:not-allowed; padding:6px 14px; font-size:0.82rem; font-weight:500; border-radius:6px;">« Previous</span>
      @else
        <a href="{{ $paginator->previousPageUrl() }}" class="btn-paces btn-paces-outline btn-paces-sm" style="padding:6px 14px; font-size:0.82rem; font-weight:500; text-decoration:none; border-radius:6px;">« Previous</a>
      @endif

      {{-- Pagination Elements --}}
      @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
          <span style="padding:4px 8px; color:var(--paces-text-muted, #64748b); font-size:0.85rem;">{{ $element }}</span>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
          @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
              <span class="btn-paces btn-paces-primary btn-paces-sm" style="min-width:34px; text-align:center; padding:6px 10px; font-size:0.82rem; font-weight:500; border-radius:6px; box-shadow:0 2px 4px rgba(62,96,213,0.25);">{{ $page }}</span>
            @else
              <a href="{{ $url }}" class="btn-paces btn-paces-outline btn-paces-sm" style="min-width:34px; text-align:center; padding:6px 10px; font-size:0.82rem; font-weight:500; text-decoration:none; border-radius:6px;">{{ $page }}</a>
            @endif
          @endforeach
        @endif
      @endforeach

      {{-- Next Page Link --}}
      @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="btn-paces btn-paces-outline btn-paces-sm" style="padding:6px 14px; font-size:0.82rem; font-weight:500; text-decoration:none; border-radius:6px;">Next »</a>
      @else
        <span class="btn-paces btn-paces-outline btn-paces-sm" style="opacity:0.45; cursor:not-allowed; padding:6px 14px; font-size:0.82rem; font-weight:500; border-radius:6px;">Next »</span>
      @endif
    </div>
  </nav>
@elseif($paginator->total() > 0)
  <div style="padding:1rem 0 0.5rem 0; font-size:0.84rem; color:var(--paces-text-muted, #64748b); font-weight:400;">
    Showing all <span style="font-weight:500; color:var(--paces-text-dark, #1e293b);">{{ $paginator->total() }}</span> results
  </div>
@endif
