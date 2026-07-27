@props(['label' => null, 'name', 'type' => 'text', 'value' => null, 'required' => false, 'placeholder' => ''])

<div>
    @if ($label)
        <label for="{{ $name }}" class="block text-xs font-semibold text-slate-300 mb-1.5">
            {{ $label }} @if($required)<span class="text-magenta">*</span>@endif
        </label>
    @endif
    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        {{ $attributes->merge(['class' => 'form-input']) }}
    >
    @error($name)
        <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
    @enderror
</div>
