@props([
    'id' => '',
    'name' => '',
    'label' => null,
    'type' => 'text',
    'value' => '',
    'required' => false,
    'title' => null,
])

@if ($label)
    <label for="{{ $id }}">
        {{ $label }} @if ($required)
            <span class="text-danger">*</span>
        @endif
        @if ($title)
            <span data-bs-toggle="tooltip" title="{{ $title }}">
                <i class="fas fa-info-circle text-info"></i>
            </span>
        @endif
    </label>
@endif

<input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}" value="{{ $value }}"
    {{ $attributes->merge(['class' => 'form-control']) }}>

@error($name)
    <span class="text-danger">{{ $message }}</span>
@enderror
