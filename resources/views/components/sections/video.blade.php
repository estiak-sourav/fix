@props([
  'content' => null,
])

@php
  $playlist = $content?->meta_data['playlist'] ?? [];
  if (empty($playlist)) {
    $playlist = [
      [
        'title' => 'Commercial Central HVAC & Chillers',
        'meta' => 'Cooling Tower AMC • Dhaka Metro',
        'video_id' => 'LXb3EKWsInQ',
        'thumb' => 'uploads/hero/slide_2.jpg',
      ],
      [
        'title' => 'High-Altitude Glass Facade Cleaning',
        'meta' => 'Certified Abseiling Team • Gulshan',
        'video_id' => 'LXb3EKWsInQ',
        'thumb' => 'uploads/hero/slide_1.jpg',
      ],
      [
        'title' => 'Industrial Substation & ATS Switchgear',
        'meta' => 'High-Voltage Audit • Savar EPZ',
        'video_id' => 'LXb3EKWsInQ',
        'thumb' => 'uploads/hero/slide_3.jpg',
      ],
    ];
  }

  $firstVideo = $playlist[0] ?? null;
  $initialVideoId = $firstVideo['video_id'] ?? 'LXb3EKWsInQ';
  $initialTitle = $content?->meta_data['current_video_title'] ?? ($firstVideo['title'] ?? 'Commercial Central HVAC & Chiller Overhaul');
  $initialMeta = $content?->meta_data['current_video_meta'] ?? ($firstVideo['meta'] ?? 'Standard Chartered Tower • Banani, Dhaka');
  $videoCount = count($playlist);
  $showPlaylist = $videoCount > 1;
@endphp

<!-- YouTube Video Portfolio Section -->
<section id="video-portfolio" class="video-portfolio-section" data-section="video_portfolio" data-section-key="video_portfolio">
  <div id="portfolioVideo" class="section-anchor-target"></div>
  <div id="video_portfolio" class="section-anchor-target"></div>
  <div class="container">
    <div class="video-portfolio-header">
      <h2 class="video-portfolio-title">{!! $content->title ?? 'Watch FIX in Action: <span>Real Project Operations</span>' !!}</h2>
    </div>

    <div class="video-portfolio-grid {{ !$showPlaylist ? 'video-single-player' : '' }}" style="{{ !$showPlaylist ? 'grid-template-columns: 1fr; max-width: 920px; margin: 0 auto;' : '' }}">
      <!-- Main Video Screen -->
      <div class="video-player-wrapper">
        <div class="video-frame-container">
          <iframe id="portfolioVideoPlayer" 
            src="https://www.youtube.com/embed/{{ $initialVideoId }}?rel=0" 
            title="FIX Facility Operations Portfolio Video"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
            allowfullscreen>
          </iframe>
        </div>
        <div class="video-player-info">
          <div>
            <div class="video-current-title" id="currentVideoTitle">{{ $initialTitle }}</div>
            <div class="video-current-meta" id="currentVideoMeta">{{ $initialMeta }}</div>
          </div>
          <a href="{{ route('booking.create') }}" class="btn btn-accent btn-sm">Request Site Inspection</a>
        </div>
      </div>

      @if($showPlaylist)
        <!-- Interactive Playlist (Scrolls smoothly within left video player height) -->
        <div class="video-playlist-wrapper" style="overflow-y: auto; max-height: 100%; display: flex; flex-direction: column; gap: 0.85rem; padding-right: 6px;">
          @foreach($playlist as $pIdx => $pItem)
            @php
              $vTitle = $pItem['title'] ?? 'Operations Video';
              $vMeta = $pItem['meta'] ?? 'Site Project';
              $vVidId = $pItem['video_id'] ?? 'LXb3EKWsInQ';
              $vThumb = $pItem['thumb'] ?? "https://img.youtube.com/vi/{$vVidId}/hqdefault.jpg";
              if (!str_starts_with($vThumb, 'http') && !str_starts_with($vThumb, 'uploads/')) {
                $vThumb = asset($vThumb);
              } elseif (str_starts_with($vThumb, 'uploads/')) {
                $vThumb = asset($vThumb);
              }
            @endphp
            <div class="video-playlist-card {{ $loop->first ? 'active' : '' }}" 
              data-video-id="{{ $vVidId }}"
              data-title="{{ $vTitle }}"
              data-meta="{{ $vMeta }}"
              style="cursor: pointer;">
              <div class="video-card-thumb-wrap">
                <img src="{{ $vThumb }}" alt="{{ $vTitle }}" style="width: 100%; height: 100%; object-fit: cover;">
                <div class="video-card-play-icon">
                  <svg viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                </div>
              </div>
              <div class="video-card-details">
                <h4>{{ $vTitle }}</h4>
                <span>{{ $vMeta }}</span>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>
</section>
