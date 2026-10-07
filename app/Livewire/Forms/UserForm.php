<?php

namespace App\Livewire\Forms;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Validation\Rules\Password;
use Livewire\Form;

class UserForm extends Form
{
    use PasswordValidationRules, ProfileValidationRules;

    public ?User $user = null;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    /**
     * Fill the form with an existing user's details for editing.
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
    }

    /**
     * @return array<string, mixed>
     */
    protected function rules(): array
    {
        return [
            ...$this->profileRules($this->user?->id),
            'password' => $this->user
                ? ['nullable', 'string', Password::default(), 'confirmed']
                : $this->passwordRules(),
        ];
    }

    /**
     * Validate the form, then create the user or update the one being edited.
     * A blank password on edit keeps the current one.
     */
    public function save(): void
    {
        $validated = $this->validate();

        if ($this->user === null) {
            User::create($validated);

            return;
        }

        $this->user->update(array_filter($validated));
    }
}
