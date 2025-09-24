@props(['for', 'width', 'border','color', 'class', 'value'])

@if($for)
<label for="{{ $for }}" class="{{ $width }} {{ $class }}">
    <span class="inline-block {{ $border }} {{ $color }} pl-1 font-semibold text-base">
        {{ $value ?? $slot }}
    </span>
</label>
@else
<div class="{{ $width }} {{ $class }}">
    <span class="inline-block {{ $border }} {{ $color }} pl-1 font-semibold text-base">
        {{ $value ?? $slot }}
    </span>
</div>
@endif