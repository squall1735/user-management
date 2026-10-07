<div {{ $attributes->class('flex gap-1') }}>
    @foreach (['th' => 'TH', 'en' => 'EN'] as $locale => $label)
        <flux:button
            size="xs"
            :variant="app()->getLocale() === $locale ? 'filled' : 'ghost'"
            :href="route('locale', $locale)"
        >
            {{ $label }}
        </flux:button>
    @endforeach
</div>
