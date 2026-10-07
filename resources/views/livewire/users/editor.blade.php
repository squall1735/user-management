<section class="w-full max-w-lg">
    <flux:heading size="xl" level="1">
        {{ $form->user ? __('Edit user') : __('Add user') }}
    </flux:heading>

    <form wire:submit="save" class="mt-6 space-y-6">
        <flux:input wire:model="form.name" :label="__('Name')" type="text" required autofocus autocomplete="off" />

        <flux:input wire:model="form.email" :label="__('Email')" type="email" required autocomplete="off" />

        <flux:input
            wire:model="form.password"
            :label="__('Password')"
            type="password"
            :required="! $form->user"
            autocomplete="new-password"
            viewable
        />
        @if ($form->user)
            <flux:text class="-mt-4 text-sm">{{ __('Leave blank to keep the current password') }}</flux:text>
        @endif

        <flux:input
            wire:model.live.blur="form.password_confirmation"
            :label="__('Confirm password')"
            type="password"
            :required="! $form->user"
            autocomplete="new-password"
            viewable
        />

        <div class="flex items-center gap-4">
            <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
            <flux:button :href="route('users.index')" wire:navigate>{{ __('Cancel') }}</flux:button>
        </div>
    </form>
</section>
