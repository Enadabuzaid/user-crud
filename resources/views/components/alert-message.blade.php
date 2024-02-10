@if(session($type))
    <div {{ $attributes->merge(['class' => $type === 'success' ? 'text-green-600' : 'text-red-600']) }}>
        {{ session($type) }}
    </div>
@endif
