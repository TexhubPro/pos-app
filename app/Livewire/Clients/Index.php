<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected int $perPage = 20;

    public bool $showModal = false;
    public bool $showConfirm = false;
    public ?int $editingId = null;
    public ?int $deleteId = null;

    public string $name = '';
    public string $phone = '';
    public string $address = '';
    public string $comment = '';
    public float $debt = 0;
    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255', 'unique:clients,phone,' . ($this->editingId ?? 'NULL')],
            'address' => ['nullable', 'string', 'max:255'],
            'comment' => ['nullable', 'string', 'max:2000'],
            'debt' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function openCreate(): void
    {
        $this->resetFields();
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $client = Client::findOrFail($id);
        $this->editingId = $client->id;
        $this->name = $client->name;
        $this->phone = $client->phone;
        $this->address = $client->address ?? '';
        $this->comment = $client->comment ?? '';
        $this->debt = (float) $client->debt;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        Client::updateOrCreate(
            ['id' => $this->editingId],
            [
                'name' => $this->name,
                'phone' => $this->phone,
                'address' => $this->address ?: null,
                'comment' => $this->comment ?: null,
                'debt' => $this->debt ?: 0,
            ]
        );

        $this->resetFields();
        session()->flash('status', __('Клиент сохранён'));
    }

    public function delete(int $id): void
    {
        $this->deleteId = $id;
        $this->showConfirm = true;
    }

    public function confirmDelete(): void
    {
        if ($this->deleteId) {
            Client::find($this->deleteId)?->delete();
            session()->flash('status', __('Клиент удалён'));
        }
        $this->showConfirm = false;
        $this->deleteId = null;
    }

    protected function resetFields(): void
    {
        $this->reset(['name', 'phone', 'address', 'comment', 'debt', 'editingId', 'showModal']);
        $this->debt = 0;
    }

    #[Layout('components.layouts.dashboard')]
    public function render()
    {
        $clients = Client::query()
            ->when($this->search, function ($q) {
                $q->where(function ($qq) {
                    $qq->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('name')
            ->paginate($this->perPage);

        return view('livewire.clients.index', [
            'clients' => $clients,
        ]);
    }
}
