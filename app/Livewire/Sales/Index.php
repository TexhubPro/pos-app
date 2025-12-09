<?php

namespace App\Livewire\Sales;

use App\Models\Client;
use App\Models\Product;
use App\Models\Sale;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected int $perPage = 20;

    public bool $showConfirm = false;
    public ?int $deleteId = null;

    #[Layout('components.layouts.dashboard')]
    public function render()
    {
        $sales = Sale::with(['client', 'product'])->latest()->paginate($this->perPage);

        return view('livewire.sales.index', [
            'sales' => $sales,
        ]);
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->showConfirm = true;
    }

    public function delete(): void
    {
        if (!$this->deleteId) {
            return;
        }

        DB::transaction(function () {
            $sale = Sale::with(['product', 'client'])->find($this->deleteId);
            if (!$sale) {
                return;
            }

            if ($sale->product) {
                $unitsPerBox = max(1, $sale->product->units_per_box ?? 1);
                $sale->product->quantity = ($sale->product->quantity ?? 0) + $sale->total_units;
                $sale->product->box_count = ($sale->product->box_count ?? 0) + ($sale->box_qty ?? 0);
                $sale->product->save();
            }

            if ($sale->client && ($sale->debt_amount ?? 0) > 0) {
                $sale->client->debt = max(0, ($sale->client->debt ?? 0) - $sale->debt_amount);
                $sale->client->save();
            }

            $sale->delete();
        });

        $this->showConfirm = false;
        $this->deleteId = null;
        session()->flash('status', __('Продажа удалена'));
    }
}
