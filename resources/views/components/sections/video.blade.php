@props([
  'content' => null,
])

<!-- YouTube Video Portfolio Section -->
<section id="portfolioVideo" class="video-portfolio-section">
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
            <div class="video-current-title" id="currentVideoTitle">Commercial Central HVAC & Chiller Overhaul</div>
            <div class="video-current-meta" id="currentVideoMeta">Standard Chartered Tower • Banani, Dhaka</div>
          </div>
          <a href="{{ route('booking.create') }}" class="btn btn-accent btn-sm">Request Site Inspection</a>
        </div>
      </div>

      <!-- Interactive Playlist -->
      <div class="video-playlist-wrapper">
        <div class="video-playlist-card active" 
          data-video-id="LXb3EKWsInQ"
          data-title="Commercial Central HVAC & Chiller Overhaul"
          data-meta="Standard Chartered Tower • Banani, Dhaka">
          <div class="video-card-thumb-wrap">
            <img src="https://images.unsplash.com/photo-1621905252507-b35492cc74b4?auto=format&fit=crop&w=300&q=80" alt="HVAC Servicing">
            <div class="video-card-play-icon">
              <svg viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
            </div>
          </div>
          <div class="video-card-details">
            <h4>Commercial Central HVAC & Chillers</h4>
            <span>Cooling Tower AMC • Dhaka Metro</span>
          </div>
        </div>

        <div class="video-playlist-card" 
          data-video-id="LXb3EKWsInQ"
          data-title="High-Rise Glass Facade Cleaning & ACP Restoration"
          data-meta="Gulshan Centre Point • Gulshan 2, Dhaka">
          <div class="video-card-thumb-wrap">
            <img src="https://images.unsplash.com/photo-1541888946425-d0fbb18086f6?auto=format&fit=crop&w=300&q=80" alt="Facade Cleaning">
            <div class="video-card-play-icon">
              <svg viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
            </div>
          </div>
          <div class="video-card-details">
            <h4>High-Altitude Glass Facade Cleaning</h4>
            <span>Certified Abseiling Team • Gulshan</span>
          </div>
        </div>

        <div class="video-playlist-card" 
          data-video-id="LXb3EKWsInQ"
          data-title="Industrial 1000 kVA Substation & Switchgear Servicing"
          data-meta="Savar Export Processing Zone (DEPZ)">
          <div class="video-card-thumb-wrap">
            <img src="https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=300&q=80" alt="Substation Maintenance">
            <div class="video-card-play-icon">
              <svg viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
            </div>
          </div>
          <div class="video-card-details">
            <h4>Industrial Substation & ATS Switchgear</h4>
            <span>High-Voltage Audit • Savar EPZ</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
