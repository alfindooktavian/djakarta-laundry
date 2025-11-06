@props(['title', 'value', 'icon', 'color' => 'gray'])

<div {{ $attributes->merge(['class' => 'flex items-center bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition p-5']) }}>
    <div class="flex items-center justify-center w-14 h-14 rounded-xl bg-{{ $color }}-100 mr-4">
        <iconify-icon icon="{{ $icon }}" class="text-3xl text-{{ $color }}-600"></iconify-icon>
    </div>

    <div>
        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">{{ $title }}</h3>
        <p class="text-3xl font-bold text-gray-900 card-value">{{ $value }}</p>
    </div>
</div>
