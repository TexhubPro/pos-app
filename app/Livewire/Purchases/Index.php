<?php

namespace App\Livewire\Purchases;

use App\Models\Category;
use App\Models\Firm;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDocument;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    protected int $perPage = 20;

    public bool $showModal = false;
    public bool $showConfirm = false;
    public ?int $editingId = null;
    public ?int $deleteId = null;

    public $product_id = '';
    public $firm_id = '';
    public float $purchase_price = 0;
    public string $payment_method = 'cash';
    public float $delivery_volume = 0;
    public float $delivery_cn_rate = 0;
    public float $delivery_tj_rate = 0;
    public int $box_qty = 1;
    public array $files = [];

    public string $search = '';
    public $categoryFilter = '';
    public $firmFilter = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter(): void
    {
        $this->resetPage();
    }

    public function updatingFirmFilter(): void
    {
        $this->resetPage();
    }

    public function updated($name, $value): void
    {
        $floatFields = [
            'purchase_price',
            'delivery_volume',
            'delivery_cn_rate',
            'delivery_tj_rate',
        ];

        if (in_array($name, $floatFields, true)) {
            $this->$name = $this->sanitizeFloat($value);
        }
    }

    protected function sanitizeFloat($value): float
    {
        if (is_string($value)) {
            $value = str_replace(',', '.', $value);
        }
        return (float) $value;
    }

    protected function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'firm_id' => ['required', 'exists:firms,id'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'in:cash,debt'],
            'delivery_volume' => ['nullable', 'numeric', 'min:0'],
            'delivery_cn_rate' => ['nullable', 'numeric', 'min:0'],
            'delivery_tj_rate' => ['nullable', 'numeric', 'min:0'],
            'box_qty' => ['required', 'integer', 'min:1'],
            'files.*' => ['nullable', 'file', 'max:5120'],
        ];
    }

    public function openCreate(): void
    {
        $this->resetFields();
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $purchase = Purchase::findOrFail($id);
        $this->editingId = $purchase->id;
        $this->product_id = $purchase->product_id;
        $this->firm_id = $purchase->firm_id;
        $this->purchase_price = (float) $purchase->purchase_price;
        $this->payment_method = $purchase->payment_method ?? 'cash';
        $this->delivery_volume = (float) ($purchase->delivery_cn_volume ?? $purchase->delivery_tj_volume ?? 0);
        $this->delivery_cn_rate = (float) ($purchase->delivery_cn_rate ?? 0);
        $this->delivery_tj_rate = (float) ($purchase->delivery_tj_rate ?? 0);
        $this->box_qty = (int) ($purchase->box_qty ?? 1);
        $this->files = [];
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        DB::transaction(function () use (&$purchase) {
            $previous = $this->editingId ? Purchase::with('product')->find($this->editingId) : null;

            $product = Product::findOrFail($this->product_id);
            $units = max(1, $product->units_per_box ?: 1);
            $deliveryCnTotal = $this->delivery_volume * $this->delivery_cn_rate;
            $deliveryTjTotal = $this->delivery_volume * $this->delivery_tj_rate;
            $goodsTotal = $this->purchase_price * $this->box_qty;
            $cashPart = $goodsTotal + $deliveryCnTotal; // всегда списываем из банка
            $boxTotal = $cashPart + $deliveryTjTotal;
            $unitCost = $boxTotal / ($units * $this->box_qty);

            // Revert previous financial impact if editing
            if ($previous) {
                $prevGoods = $previous->purchase_price * ($previous->box_qty ?? 1);
                $prevCn = $previous->delivery_cn ?? 0;
                $prevTj = $previous->delivery_tj ?? 0;
                $prevCash = $prevGoods + $prevCn;

                if (($previous->payment_method ?? 'cash') === 'cash') {
                    \App\Models\BankTransaction::create([
                        'type' => 'deposit',
                        'amount' => $prevCash + $prevTj,
                        'source' => $previous->firm?->name,
                        'comment' => __('Коррекция при редактировании закупки'),
                    ]);
                } else {
                    $firm = Firm::lockForUpdate()->find($previous->firm_id);
                    \App\Models\BankTransaction::create([
                        'type' => 'deposit',
                        'amount' => $prevCash,
                        'source' => $previous->firm?->name,
                        'comment' => __('Коррекция при редактировании закупки'),
                    ]);
                    if ($firm) {
                        $firm->debt = max(0, ($firm->debt ?? 0) - $prevTj);
                        $firm->save();
                    }
                }
            }

            $purchase = Purchase::updateOrCreate(
                ['id' => $this->editingId],
                [
                    'product_id' => $this->product_id,
                    'firm_id' => $this->firm_id,
                    'purchase_price' => $this->purchase_price,
                    'payment_method' => $this->payment_method,
                    'delivery_cn_volume' => $this->delivery_volume,
                    'delivery_cn_rate' => $this->delivery_cn_rate,
                    'delivery_cn' => $deliveryCnTotal,
                    'delivery_tj_volume' => $this->delivery_volume,
                    'delivery_tj_rate' => $this->delivery_tj_rate,
                    'delivery_tj' => $deliveryTjTotal,
                    'cost_per_unit' => $unitCost,
                    'box_qty' => $this->box_qty,
                ]
            );

            if ($previous && $previous->product) {
                $oldUnitsPerBox = max(0, $previous->product->units_per_box ?? 0);
                $previous->product->box_count = max(0, ($previous->product->box_count ?? 0) - ($previous->box_qty ?? 0));
                $previous->product->quantity = max(0, ($previous->product->quantity ?? 0) - (($previous->box_qty ?? 0) * $oldUnitsPerBox));
                $previous->product->save();
            }

            if ($purchase->product) {
                $newUnitsPerBox = max(0, $purchase->product->units_per_box ?? 0);
                $purchase->product->box_count = ($purchase->product->box_count ?? 0) + $this->box_qty;
                $purchase->product->quantity = ($purchase->product->quantity ?? 0) + ($this->box_qty * $newUnitsPerBox);
                $purchase->product->save();
            }

            $purchase->received_box_qty = ($purchase->received_box_qty ?? 0);
            $purchase->save();

            // Apply financial impact for new/updated record
            $firm = Firm::lockForUpdate()->find($this->firm_id);
            \App\Models\BankTransaction::create([
                'type' => 'withdraw',
                'amount' => $cashPart + ($this->payment_method === 'cash' ? $deliveryTjTotal : 0),
                'source' => $firm?->name,
                'comment' => __('Закупка товара'),
            ]);

            if ($this->payment_method === 'debt' && $firm) {
                $firm->debt = ($firm->debt ?? 0) + $deliveryTjTotal;
                $firm->save();

                if ($deliveryTjTotal > 0 && (!$previous || ($previous->payment_method ?? 'cash') !== 'debt')) {
                    \App\Models\FirmPayment::create([
                        'firm_id' => $firm->id,
                        'amount' => $deliveryTjTotal,
                        'method' => 'debt',
                        'comment' => __('Доставка TJ в долг по закупке'),
                    ]);
                }
            }

            if (!empty($this->files)) {
                foreach ($this->files as $file) {
                    $stored = $file->store('purchase-docs', 'public');
                    PurchaseDocument::create([
                        'purchase_id' => $purchase->id,
                        'path' => Storage::url($stored),
                        'original_name' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                    ]);
                }
            }
        });

        $this->resetFields();
        session()->flash('status', __('Закупка сохранена'));
    }

    public function delete(int $id): void
    {
        $this->deleteId = $id;
        $this->showConfirm = true;
    }

    public function confirmDelete(): void
    {
        if ($this->deleteId) {
            DB::transaction(function () {
                $purchase = Purchase::with('product', 'documents')->find($this->deleteId);
                if ($purchase && $purchase->product) {
                    $units = max(0, $purchase->product->units_per_box ?? 0);
                    $purchase->product->box_count = max(0, ($purchase->product->box_count ?? 0) - ($purchase->box_qty ?? 0));
                    $purchase->product->quantity = max(0, ($purchase->product->quantity ?? 0) - (($purchase->box_qty ?? 0) * $units));
                    $purchase->product->save();
                }

                if ($purchase) {
                    $goods = $purchase->purchase_price * ($purchase->box_qty ?? 1);
                    $cn = $purchase->delivery_cn ?? 0;
                    $tj = $purchase->delivery_tj ?? 0;
                    $cashBack = $goods + $cn;

                    if (($purchase->payment_method ?? 'cash') === 'cash') {
                        \App\Models\BankTransaction::create([
                            'type' => 'deposit',
                            'amount' => $cashBack + $tj,
                            'source' => $purchase->firm?->name,
                            'comment' => __('Возврат при удалении закупки'),
                        ]);
                    } else {
                        \App\Models\BankTransaction::create([
                            'type' => 'deposit',
                            'amount' => $cashBack,
                            'source' => $purchase->firm?->name,
                            'comment' => __('Возврат при удалении закупки'),
                        ]);
                        $firm = Firm::lockForUpdate()->find($purchase->firm_id);
                        if ($firm) {
                            $firm->debt = max(0, ($firm->debt ?? 0) - $tj);
                            $firm->save();
                        }
                    }
                }

                if ($purchase) {
                    $purchase->documents()->delete();
                    $purchase->delete();
                }
            });

            session()->flash('status', __('Закупка удалена'));
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
            'product_id',
            'firm_id',
            'purchase_price',
            'delivery_volume',
            'delivery_cn_rate',
            'delivery_tj_rate',
            'box_qty',
            'payment_method',
            'files',
            'editingId',
            'showModal',
        ]);
        $this->purchase_price = 0;
        $this->delivery_volume = 0;
        $this->delivery_cn_rate = 0;
        $this->delivery_tj_rate = 0;
        $this->box_qty = 1;
        $this->payment_method = 'cash';
        $this->files = [];
    }

    #[Layout('components.layouts.dashboard')]
    public function render()
    {
        $firms = Firm::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        $purchases = Purchase::query()
            ->with(['product', 'firm', 'product.category', 'documents', 'receipts'])
            ->when($this->search, function ($q) {
                $q->whereHas('product', fn ($p) => $p->where('name', 'like', '%' . $this->search . '%'));
            })
            ->when($this->categoryFilter, function ($q) {
                $q->whereHas('product', fn ($p) => $p->where('category_id', $this->categoryFilter));
            })
            ->when($this->firmFilter, fn ($q) => $q->where('firm_id', $this->firmFilter))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.purchases.index', [
            'purchases' => $purchases,
            'firms' => $firms,
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
