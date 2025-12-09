<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;

class Index extends Component
{
    use WithPagination;

    protected int $perPage = 20;

    public bool $showModal = false;
    public ?int $editingId = null;

    public string $name = '';
    public string $phone = '';
    public string $password = '';

    public string $search = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50', 'unique:users,phone,' . $this->editingId],
            'password' => [$this->editingId ? 'nullable' : 'required', 'string', 'min:6'],
        ];
    }

    public function openCreate(): void
    {
        $this->resetFields();
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $user = User::findOrFail($id);
        $this->editingId = $user->id;
        $this->name = $user->name ?? '';
        $this->phone = $user->phone ?? '';
        $this->password = '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'phone' => $this->phone,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        User::updateOrCreate(['id' => $this->editingId], $data);

        $this->resetFields();
        session()->flash('status', __('Пользователь сохранён'));
    }

    protected function resetFields(): void
    {
        $this->reset([
            'name',
            'phone',
            'password',
            'editingId',
            'showModal',
        ]);
    }

    #[Layout('components.layouts.dashboard')]
    public function render()
    {
        $users = User::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%')->orWhere('phone', 'like', '%' . $this->search . '%'))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.users.index', [
            'users' => $users,
        ]);
    }
}
