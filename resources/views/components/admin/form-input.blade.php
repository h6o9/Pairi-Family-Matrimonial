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

@if ($type === 'password')
    <div class="password-input-wrap">
        <input id="{{ $id }}" name="{{ $name }}" type="password" value="{{ $value }}"
            data-password-toggle-ready="1"
            {{ $attributes->merge(['class' => 'form-control']) }}>
        <button type="button" class="password-toggle-btn" aria-label="Show password" tabindex="0">
            <i class="fas fa-eye-slash" aria-hidden="true"></i>
        </button>
    </div>
@else
    <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}" value="{{ $value }}"
        {{ $attributes->merge(['class' => 'form-control']) }}>
@endif

@error($name)
    <span class="text-danger">{{ $message }}</span>
@enderror
