@props([
  'content' => null,
  'categories' => [],
  'services' => [],
])

<x-sections.hero :content="$content" :categories="$categories" :services="$services" />

