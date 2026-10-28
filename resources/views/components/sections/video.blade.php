@props([
  'content' => null,
])

@php
  $currentTitle = $content?->meta_data['current_video_title'] ?? ($content?->meta_data['default_title'] ?? 'Commercial Central HVAC & Chiller Overhaul');
  $currentMeta = $content?->meta_data['current_video_meta'] ?? ($content?->meta_data['default_meta'] ?? 'Standard Chartered Tower • Banani, Dhaka');

  $defaultPlaylist = [
    1 => [
      'title' => 'Commercial Central HVAC & Chillers',
      'meta' => 'Cooling Tower AMC • Dhaka Metro',
      'video_id' => 'LXb3EKWsInQ',
      'thumb' => 'uploads/hero/slide_2.jpg',
    ],
    2 => [
      'title' => 'High-Altitude Glass Facade Cleaning',
      'meta' => 'Certified Abseiling Team • Gulshan',
      'video_id' => 'LXb3EKWsInQ',
      'thumb' => 'uploads/hero/slide_1.jpg',
    ],
    3 => [
      'title' => 'Industrial Substation & ATS Switchgear',
      'meta' => 'High-Voltage Audit • Savar EPZ',
      'video_id' => 'LXb3EKWsInQ',
      'thumb' => 'uploads/hero/slide_3.jpg',
    ],
  ];

  $customPlaylist = $content?->meta_data['playlist'] ?? [];
@endphp

<!-- YouTube Video Portfolio Section -->
<section id="video-portfolio" class="video-portfolio-section" data-section="video_portfolio" data-section-key="video_portfolio">
  <div id="portfolioVideo" class="section-anchor-target"></div>
  <div id="video_portfolio" class="section-anchor-target"></div>
  <div class="container">
    <div class="video-portfolio-header">
      <h2 class="video-portfolio-title">{!! $content->title ?? 'Watch FIX in Action: <span>Real Project Operations</span>' !!}</h2>
    </div>

    <div class="video-portfolio-grid">
      <!-- Main Video Screen -->
      <div class="video-player-wrapper">
        <div class="video-frame-container">
          <iframe id="portfolioVideoPlayer" 
            src="{{ $content->media_path ?? 'https://www.youtube.com/embed/LXb3EKWsInQ?rel=0' }}" 
            title="FIX Facility Operations Portfolio Video"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
            allowfullscreen>
          </iframe>
        </div>
        <div class="video-player-info">
          <div>
            <div class="video-current-title" id="currentVideoTitle">{{ $currentTitle }}</div>
            <div class="video-current-meta" id="currentVideoMeta">{{ $currentMeta }}</div>
          </div>
          <a href="{{ route('booking.create') }}" class="btn btn-accent btn-sm">Request Site Inspection</a>
        </div>
      </div>

      <!-- Interactive Playlist -->
      <div class="video-playlist-wrapper">
        @foreach($defaultPlaylist as $pNum => $pItem)
          @php
            $itemTitle = $customPlaylist[$pNum]['title'] ?? $pItem['title'];
            $itemMeta = $customPlaylist[$pNum]['meta'] ?? $pItem['meta'];
            $itemVidId = $customPlaylist[$pNum]['video_id'] ?? $pItem['video_id'];
          @endphp
          <div class="video-playlist-card {{ $loop->first ? 'active' : '' }}" 
            data-video-id="{{ $itemVidId }}"
            data-title="{{ $itemTitle }}"
            data-meta="{{ $itemMeta }}">
            <div class="video-card-thumb-wrap">
              <img src="{{ asset($pItem['thumb']) }}" alt="{{ $itemTitle }}">
              <div class="video-card-play-icon">
                <svg viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
              </div>
            </div>
            <div class="video-card-details">
              <h4>{{ $itemTitle }}</h4>
              <span>{{ $itemMeta }}</span>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</section>
