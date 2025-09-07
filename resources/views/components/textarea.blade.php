@props(['id' => null, 'name', 'rows' => 4])

<textarea
    id="{{ $id ?? $name }}"
    name="{{ $name }}"
    rows="{{ $rows }}"
    {{ $attributes->merge(['class' =>
      'mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 ' .
      'bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 ' .
      'focus:border-indigo-500 focus:ring-indigo-500 shadow-sm'
  ]) }}>{{ $slot ?? old($name) }}</textarea>