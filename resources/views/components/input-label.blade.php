@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-base text-gray-700 dark:text-gray-300 border-l-8 border-l-brand pl-1']) }}>
    {{ $value ?? $slot }}
</label>
