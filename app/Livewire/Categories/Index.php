<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected int $perPage = 20;

    public bool $showModal = false;
    public ?int $editingId = null;
    public string $name = '';
    public string $search = '';
    public string $sortField = 'name';
    public string $sortDirection = 'asc';
    public bool $showConfirm = false;
    public ?int $deleteId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . ($this->editingId ?? 'NULL')],
        ];
    }

    protected array $validationAttributes = [
        'name' => 'Название категории',
    ];

    public function openCreate(): void
    {
        $this->reset(['name', 'editingId']);
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $category = Category::findOrFail($id);
        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        Category::updateOrCreate(
            ['id' => $this->editingId],
            ['name' => $this->name]
        );

        $this->reset(['name', 'editingId', 'showModal']);
        session()->flash('status', __('Категория сохранена'));
    }

    public function delete(int $id): void
    {
        $this->deleteId = $id;
        $this->showConfirm = true;
    }

    public function confirmDelete(): void
    {
        if ($this->deleteId) {
            Category::find($this->deleteId)?->delete();
            session()->flash('status', __('Категория удалена'));
        }
        $this->showConfirm = false;
        $this->deleteId = null;
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

    #[Layout('components.layouts.dashboard')]
    public function render()
    {
        $categories = Category::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.categories.index', [
            'categories' => $categories,
        ]);
    }
}
