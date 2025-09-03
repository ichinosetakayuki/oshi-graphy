@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-base border-l-8 border-l-brand pl-1 text-gray-700 dark:text-gray-300']) }}>
    {{ $value ?? $slot }}
</label>
