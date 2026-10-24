<x-layouts.app title="FIX - Building & Facility Maintenance Services">
  <!-- Hero & Quick Booking Strip Component -->
  <x-sections.hero :content="$siteContents['hero'] ?? null" :categories="$categories" :services="$services" />

  <!-- Corporate Partners Marquee Component -->
  <x-sections.clients :content="$siteContents['clients'] ?? null" />

  <!-- Dynamic Service Portfolio Component -->
  <x-sections.services :content="$siteContents['services'] ?? null" :categories="$categories" :services="$services" />

  <!-- Annual Maintenance Contracts (AMC) Component -->
  <x-sections.amc-plans :content="$siteContents['amc_plans'] ?? null" :plans="$amcPlans" />

  <!-- How It Works Flow Component -->
  <x-sections.how-it-works :content="$siteContents['how_it_works'] ?? null" />

  <!-- YouTube Video Operations Portfolio Component -->
  <x-sections.video :content="$siteContents['video_portfolio'] ?? null" />

  <!-- Value Proposition & Why FIX Component -->
  <x-sections.why-fix :content="$siteContents['why_fix'] ?? null" />

  <!-- Industry Sectors (Who We Serve) Component -->
  <x-sections.who-we-serve :content="$siteContents['who_we_serve'] ?? null" :sectors="$sectors" />

  <!-- Client Testimonials Component -->
  <x-sections.testimonials :content="$siteContents['testimonials'] ?? null" :testimonials="$testimonials" />

  <!-- Frequently Asked Questions Component -->
  <x-sections.faq :content="$siteContents['faq'] ?? null" :faqs="$faqs" />

  <!-- Operations Command Contact Component -->
  <x-sections.contact :content="$siteContents['contact'] ?? null" />
</x-layouts.app>
