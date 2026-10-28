<x-layouts.app title="FIX - Building & Facility Maintenance Services">
  <!-- Hero & Quick Booking Strip Component -->
  @if(!isset($siteContents['hero']) || ($siteContents['hero']->is_active ?? true))
    <x-sections.hero :content="$siteContents['hero'] ?? null" :categories="$categories" :services="$services" />
  @endif

  <!-- Corporate Partners Marquee Component -->
  @if(!isset($siteContents['clients']) || ($siteContents['clients']->is_active ?? true))
    <x-sections.clients :content="$siteContents['clients'] ?? null" />
  @endif

  <!-- Dynamic Service Portfolio Component -->
  @if(!isset($siteContents['services']) || ($siteContents['services']->is_active ?? true))
    <x-sections.services :content="$siteContents['services'] ?? null" :categories="$categories" :services="$services" />
  @endif

  <!-- Annual Maintenance Contracts (AMC) Component -->
  @if(!isset($siteContents['amc_plans']) || ($siteContents['amc_plans']->is_active ?? true))
    <x-sections.amc-plans :content="$siteContents['amc_plans'] ?? null" :plans="$amcPlans" />
  @endif

  <!-- How It Works Flow Component -->
  @if(!isset($siteContents['how_it_works']) || ($siteContents['how_it_works']->is_active ?? true))
    <x-sections.how-it-works :content="$siteContents['how_it_works'] ?? null" />
  @endif

  <!-- YouTube Video Operations Portfolio Component -->
  @if(!isset($siteContents['video_portfolio']) || ($siteContents['video_portfolio']->is_active ?? true))
    <x-sections.video :content="$siteContents['video_portfolio'] ?? null" />
  @endif

  <!-- Value Proposition & Why FIX Component -->
  @if(!isset($siteContents['why_fix']) || ($siteContents['why_fix']->is_active ?? true))
    <x-sections.why-fix :content="$siteContents['why_fix'] ?? null" />
  @endif

  <!-- Industry Sectors (Who We Serve) Component -->
  @if(!isset($siteContents['who_we_serve']) || ($siteContents['who_we_serve']->is_active ?? true))
    <x-sections.who-we-serve :content="$siteContents['who_we_serve'] ?? null" :sectors="$sectors" />
  @endif

  <!-- Client Testimonials Component -->
  @if(!isset($siteContents['testimonials']) || ($siteContents['testimonials']->is_active ?? true))
    <x-sections.testimonials :content="$siteContents['testimonials'] ?? null" :testimonials="$testimonials" />
  @endif

  <!-- Frequently Asked Questions Component -->
  @if(!isset($siteContents['faq']) || ($siteContents['faq']->is_active ?? true))
    <x-sections.faq :content="$siteContents['faq'] ?? null" :faqs="$faqs" />
  @endif

  <!-- Operations Command Contact Component -->
  @if(!isset($siteContents['contact']) || ($siteContents['contact']->is_active ?? true))
    <x-sections.contact :content="$siteContents['contact'] ?? null" />
  @endif
</x-layouts.app>
