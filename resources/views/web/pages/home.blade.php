<x-layouts.app title="FIX - Building & Facility Maintenance Services">
  <!-- Hero & Quick Booking Strip Component -->
  <x-sections.hero :content="$siteContents['hero'] ?? null" :categories="$categories" :services="$services" />

  <!-- Corporate Partners Marquee Component -->
  <x-sections.clients />

  <!-- Dynamic Service Portfolio Component -->
  <x-sections.services :categories="$categories" :services="$services" />

  <!-- Annual Maintenance Contracts (AMC) Component -->
  <x-sections.amc-plans :plans="$amcPlans" />

  <!-- How It Works Flow Component -->
  <x-sections.how-it-works />

  <!-- YouTube Video Operations Portfolio Component -->
  <x-sections.video :content="$siteContents['video_portfolio'] ?? null" />

  <!-- Value Proposition & Why FIX Component -->
  <x-sections.why-fix />

  <!-- Industry Sectors (Who We Serve) Component -->
  <x-sections.who-we-serve :sectors="$sectors" />

  <!-- Client Testimonials Component -->
  <x-sections.testimonials :testimonials="$testimonials" />

  <!-- Frequently Asked Questions Component -->
  <x-sections.faq :faqs="$faqs" />

  <!-- Operations Command Contact Component -->
  <x-sections.contact />
</x-layouts.app>
