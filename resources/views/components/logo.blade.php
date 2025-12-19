@props(['size' => 128])

<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" {{ $attributes->merge(['style' => "width: {$size}px; height: {$size}px;", ]) }}>
    <text y="26" font-size="24">🔮</text>
</svg>
