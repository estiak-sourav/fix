@props([
    'title',
    'subtitle' => null,
    'badge' => null,
    'maxWidth' => '840px',
    'style' => null,
])

<x-page-hero :title="$title" :subtitle="$subtitle" :badge="$badge" :maxWidth="$maxWidth" :style="$style">
  {{ $slot }}
</x-page-hero>
