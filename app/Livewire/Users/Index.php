<?php

namespace App\Livewire\Users;

use App\Models\User;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Users')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public ?int $deletingId = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        abort_if($id === auth()->id(), 403);

        $this->deletingId = $id;

        Flux::modal('delete-user')->show();
    }

    public function delete(): void
    {
        abort_if($this->deletingId === auth()->id(), 403);

        User::whereKey($this->deletingId)->delete();

        $this->deletingId = null;

        Flux::modal('delete-user')->close();
        Flux::toast(variant: 'success', text: __('User deleted.'));
    }

    public function render(): View
    {
        $users = User::query()
            ->when($this->search !== '', fn ($query) => $query
                ->where(fn ($query) => $query
                    ->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")))
            ->latest()
            ->latest('id')
            ->paginate(5);

        return view('livewire.users.index', ['users' => $users]);
    }
}
