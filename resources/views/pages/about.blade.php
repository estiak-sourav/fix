<x-layouts.app title="About Us | FIX Facility Management">
  <x-page-hero 
    :title="$heroContent->title ?? 'Pioneering Modern Facility Engineering in Bangladesh'"
    :subtitle="$heroContent->subtitle ?? 'FIX is Bangladesh\'s premier technology-driven facility management company, delivering MEP engineering, HVAC solutions, and compliant operations.'"
    maxWidth="900px"
  />

  @include('web.pages.about')
</x-layouts.app>
