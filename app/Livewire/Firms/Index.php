<?php

namespace App\Livewire\Firms;

use App\Models\Firm;
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
            'phone' => ['nullable', 'string', 'max:255'],
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
        $firm = Firm::findOrFail($id);
        $this->editingId = $firm->id;
        $this->name = $firm->name;
        $this->phone = $firm->phone ?? '';
        $this->debt = (float) $firm->debt;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        Firm::updateOrCreate(
            ['id' => $this->editingId],
            [
                'name' => $this->name,
                'phone' => $this->phone ?: null,
                'debt' => $this->debt ?: 0,
            ]
        );

        $this->resetFields();
        session()->flash('status', __('Фирма сохранена'));
    }

    public function delete(int $id): void
    {
        $this->deleteId = $id;
        $this->showConfirm = true;
    }

    public function confirmDelete(): void
    {
        if ($this->deleteId) {
            Firm::find($this->deleteId)?->delete();
            session()->flash('status', __('Фирма удалена'));
        }
        $this->showConfirm = false;
        $this->deleteId = null;
    }

    protected function resetFields(): void
    {
        $this->reset(['name', 'phone', 'debt', 'editingId', 'showModal']);
        $this->debt = 0;
    }

    #[Layout('components.layouts.dashboard')]
    public function render()
    {
        $firms = Firm::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->orderBy('name')
            ->paginate($this->perPage);

        return view('livewire.firms.index', [
            'firms' => $firms,
        ]);
    }
}
