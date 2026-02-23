@props(['disabled' => false])

<textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' =>
        'form-control border border-secondary rounded px-3 py-2 shadow-sm bg-white text-dark dark:bg-dark dark:text-light focus:border-primary focus:ring-primary',
]) !!}></textarea>
