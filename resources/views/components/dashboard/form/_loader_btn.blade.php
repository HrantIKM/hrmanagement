<button class="btn {{ $type ?? 'btn-primary' }} {{ $class ?? 'loading-btn' }}" @if(!empty($disabled)) disabled @endif>
    {{ __('button.'.($text ?? 'search')) }}
    <div class="spinner-border" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</button>
