<section class="w-full max-w-lg">
    <flux:heading size="xl" level="1">{{ __('Edit profile') }}</flux:heading>
    <flux:subheading size="lg">{{ __('Update your name, email address and password') }}</flux:subheading>

    <form wire:submit="save" class="mt-6 space-y-6">
        <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name" />

        <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

        <flux:separator />

        <div>
            <flux:heading>{{ __('Change password') }}</flux:heading>
            <flux:text class="mt-1 text-sm">{{ __('Leave blank to keep your current password') }}</flux:text>
        </div>

        <flux:input
            wire:model="current_password"
            :label="__('Current password')"
            type="password"
            autocomplete="current-password"
            viewable
        />

        <flux:input
            wire:model="password"
            :label="__('New password')"
            type="password"
            autocomplete="new-password"
            viewable
        />

        <flux:input
            wire:model.live.blur="password_confirmation"
            :label="__('Confirm new password')"
            type="password"
            autocomplete="new-password"
            viewable
        />

        <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
    </form>
</section>
