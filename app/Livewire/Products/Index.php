<?php

namespace App\Livewire\Products;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithFileUploads, WithPagination;

    protected int $perPage = 20;

    public bool $showModal = false;
    public bool $showConfirm = false;
    public ?int $editingId = null;
    public ?int $deleteId = null;

    public string $name = '';
    public int $quantity = 0;
    public string $photo = '';
    public $photoFile = null;
    public string $barcode = '';
    public int $box_count = 0;
    public int $units_per_box = 0;
    public $category_id = '';

    public string $search = '';
    public string $sortField = 'name';
    public string $sortDirection = 'asc';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryId(): void
    {
        $this->resetPage();
    }

    public function updated($name, $value): void
    {
        if (in_array($name, ['box_count', 'units_per_box'], true)) {
            $boxes = max(0, (int) $this->box_count);
            $units = max(0, (int) $this->units_per_box);
            $autoQuantity = $boxes * $units;
            if ($autoQuantity > 0) {
                $this->quantity = $autoQuantity;
            }
        }
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:0'],
            'photo' => ['nullable', 'string', 'max:1024'],
            'photoFile' => ['nullable', 'image', 'max:4096'],
            'barcode' => ['nullable', 'string', 'max:255'],
            'box_count' => ['required', 'integer', 'min:0'],
            'units_per_box' => ['required', 'integer', 'min:0'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ];
    }

    public function openCreate(): void
    {
        $this->resetFields();
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $product = Product::findOrFail($id);
        $this->editingId = $product->id;
        $this->name = $product->name;
        $this->quantity = $product->quantity;
        $this->photo = $product->photo ?? '';
        $this->photoFile = null;
        $this->barcode = $product->barcode ?? '';
        $this->box_count = $product->box_count ?? 0;
        $this->units_per_box = $product->units_per_box ?? 0;
        $this->category_id = $product->category_id;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $photoPath = $this->photo ?: null;

        if ($this->photoFile) {
            $stored = $this->photoFile->store('products', 'public');
            $photoPath = Storage::url($stored);
        }

        Product::updateOrCreate(
            ['id' => $this->editingId],
            [
                'name' => $this->name,
                'quantity' => $this->quantity,
                'photo' => $photoPath,
                'barcode' => $this->barcode ?: null,
                'box_count' => $this->box_count,
                'units_per_box' => $this->units_per_box,
                'category_id' => $this->category_id ?: null,
            ]
        );

        $this->resetFields();
        session()->flash('status', __('Товар сохранён'));
    }

    public function delete(int $id): void
    {
        $this->deleteId = $id;
        $this->showConfirm = true;
    }

    public function confirmDelete(): void
    {
        if ($this->deleteId) {
            Product::find($this->deleteId)?->delete();
            session()->flash('status', __('Товар удалён'));
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

    protected function resetFields(): void
    {
        $this->reset([
            'name',
            'quantity',
            'photo',
            'photoFile',
            'barcode',
            'box_count',
            'units_per_box',
            'category_id',
            'editingId',
            'showModal',
        ]);
        $this->quantity = 0;
        $this->box_count = 0;
        $this->units_per_box = 0;
    }

    #[Layout('components.layouts.dashboard')]
    public function render()
    {
        $categories = Category::orderBy('name')->get();
        $products = Product::query()
            ->when($this->search, function ($q) {
                $q->where(function ($qq) {
                    $qq->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('barcode', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->category_id, fn ($q) => $q->where('category_id', $this->category_id))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.products.index', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
