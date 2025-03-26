@props(['disabled' => false])

{{-- <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm']) !!}> --}}
<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' =>
        'form-control border border-secondary rounded px-3 py-2 shadow-sm bg-white text-dark dark:bg-dark dark:text-light focus:border-primary focus:ring-primary',
]) !!}>
