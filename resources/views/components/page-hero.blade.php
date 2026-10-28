@props([
    'title',
    'subtitle' => null,
    'badge' => null,
    'maxWidth' => '840px',
    'style' => null,
])

<section class="page-hero" style="background:linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding:3.5rem 1.5rem 2.75rem; color:#ffffff; text-align:center; {{ $style }}">
  <div class="container" style="max-width:{{ $maxWidth }}; margin:0 auto; text-align:center;">
    @if($badge)
      <span style="display:inline-block; font-size:0.75rem; font-weight:700; text-transform:uppercase; color:#38bdf8; letter-spacing:0.06em; margin-bottom:10px; padding:3px 12px; border-radius:20px; background:rgba(56,189,248,0.12); border:1px solid rgba(56,189,248,0.25);">
        {{ $badge }}
      </span>
    @endif

    <h1 style="font-size:2.25rem; font-weight:800; color:#ffffff; margin:0 auto 0.65rem; text-align:center; line-height:1.25; letter-spacing:-0.02em;">
      {{ $title }}
    </h1>

    @if($subtitle)
      <p style="font-size:0.975rem; color:#cbd5e1; margin:0 auto; text-align:center; line-height:1.6; max-width:620px;">
        {{ $subtitle }}
      </p>
    @endif

    {{ $slot }}
  </div>
</section>
