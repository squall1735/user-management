<?php

namespace App\Livewire\Users;

use App\Livewire\Forms\UserForm;
use App\Models\User;
use Flux\Flux;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Users')]
class Editor extends Component
{
    public UserForm $form;

    /**
     * Mount the component. No user means the create form.
     */
    public function mount(?User $user = null): void
    {
        if ($user?->exists) {
            $this->form->setUser($user);
        }
    }

    public function save(): void
    {
        $isNew = $this->form->user === null;

        $this->form->save();

        Flux::toast(
            variant: 'success',
            text: $isNew ? __('User created.') : __('User updated.'),
        );

        $this->redirectRoute('users.index', navigate: true);
    }
}
