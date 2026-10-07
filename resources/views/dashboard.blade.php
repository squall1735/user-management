<x-layouts::app :title="__('Dashboard')">
    <div class="flex flex-col gap-6">
        <div>
            <flux:heading size="xl" level="1">{{ __('Welcome, :name', ['name' => auth()->user()->name]) }}</flux:heading>
            <flux:subheading size="lg">{{ __('Here is a summary of your system') }}</flux:subheading>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <flux:card>
                <flux:text>{{ __('Total users') }}</flux:text>
                <flux:heading size="xl" class="mt-2">{{ number_format($totalUsers) }}</flux:heading>
            </flux:card>

            <flux:card>
                <flux:text>{{ __('Registered today') }}</flux:text>
                <flux:heading size="xl" class="mt-2">{{ number_format($usersToday) }}</flux:heading>
            </flux:card>
        </div>
    </div>
</x-layouts::app>
