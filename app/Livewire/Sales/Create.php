<?php

namespace App\Livewire\Sales;

use App\Models\Client;
use App\Models\ClientPayment;
use App\Models\Product;
use App\Models\Sale;
use App\Models\BankTransaction;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Create extends Component
{
    public $client_id = '';
    public $product_id = '';
    public $price_unit = 0;
    public $price_box = 0;
    public $box_qty = 0;
    public $unit_qty = 0;
    public string $payment_type = 'cash';
    public string $payment_method = 'cash';
    public $cash_amount = 0;
    public $debt_amount = 0;
    public string $comment = '';

    protected bool $syncing = false;

    protected function rules(): array
    {
        return [
            'client_id' => ['required', 'exists:clients,id'],
            'product_id' => ['required', 'exists:products,id'],
            'price_unit' => ['nullable', 'numeric', 'min:0'],
            'price_box' => ['nullable', 'numeric', 'min:0'],
            'box_qty' => ['nullable', 'integer', 'min:0'],
            'unit_qty' => ['nullable', 'integer', 'min:0'],
            'payment_type' => ['required', 'string', 'in:cash,debt,mixed'],
            'payment_method' => ['required', 'string', 'in:cash,card_milli,dushanbe_city,alif'],
            'cash_amount' => ['nullable', 'numeric', 'min:0'],
            'debt_amount' => ['nullable', 'numeric', 'min:0'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function submit(): void
    {
        $this->sanitizeNumericFields();
        $this->validate();

        DB::transaction(function () {
            $product = Product::lockForUpdate()->findOrFail($this->product_id);
            $client = Client::lockForUpdate()->findOrFail($this->client_id);

            $totals = $this->calculateTotals();
            $boxQty = $totals['boxQty'];
            $unitQty = $totals['unitQty'];
            $unitsPerBox = $totals['unitsPerBox'];
            $totalUnits = $totals['totalUnits'];

            if ($totalUnits <= 0) {
                $this->flashQuantityError(__('Нужно указать количество для продажи'));
                return;
            }
            if (($product->quantity ?? 0) < $totalUnits) {
                $this->flashQuantityError(__('Недостаточно товара на складе'));
                return;
            }

            $boxesUsed = $boxQty + (int) ceil($unitQty / $unitsPerBox);
            if (($product->box_count ?? 0) < $boxesUsed) {
                $this->flashQuantityError(__('Недостаточно коробок на складе'));
                return;
            }

            $pricePerUnit = $this->toFloat($this->price_unit);
            $pricePerBox = $this->toFloat($this->price_box);
            if ($pricePerUnit <= 0 && $pricePerBox > 0 && $unitsPerBox > 0) {
                $pricePerUnit = $pricePerBox / $unitsPerBox;
            }
            if ($pricePerBox <= 0 && $pricePerUnit > 0) {
                $pricePerBox = $pricePerUnit * $unitsPerBox;
            }

            $totalPrice = $pricePerUnit * $totalUnits;

            $cash = 0;
            $debt = 0;
            if ($this->payment_type === 'cash') {
                $cash = $totalPrice;
            } elseif ($this->payment_type === 'debt') {
                $debt = $totalPrice;
            } else {
                $cashInput = max(0, $this->toFloat($this->cash_amount));
                $cash = min($cashInput, $totalPrice);
                $debt = max(0, $totalPrice - $cash);
            }

            $product->quantity = max(0, ($product->quantity ?? 0) - $totalUnits);
            $product->box_count = max(0, ($product->box_count ?? 0) - $boxesUsed);
            $product->save();

            if ($debt > 0) {
                $client->debt = ($client->debt ?? 0) + $debt;
                $client->save();
            }

            $sale = Sale::create([
                'client_id' => $this->client_id,
                'product_id' => $this->product_id,
                'price' => $pricePerUnit,
                'box_qty' => $boxQty,
                'unit_qty' => $unitQty,
                'total_units' => $totalUnits,
                'total_price' => $totalPrice,
                'payment_type' => $this->payment_type,
                'payment_method' => $this->payment_method,
                'cash_amount' => $cash,
                'debt_amount' => $debt,
                'comment' => $this->comment ?: null,
            ]);

            if ($cash > 0) {
                BankTransaction::create([
                    'type' => 'deposit',
                    'amount' => $cash,
                    'method' => $this->payment_method,
                    'source' => $client->name,
                    'comment' => __('Продажа товара'),
                ]);
            }

            if ($debt > 0) {
                ClientPayment::create([
                    'client_id' => $client->id,
                    'amount' => $debt,
                    'method' => 'debt',
                    'comment' => __('Продажа в долг (#') . $sale->id . ')',
                ]);
            }
        });

        $this->resetForm();
        session()->flash('status', __('Продажа оформлена'));
    }

    protected function resetForm(): void
    {
        $this->reset(['client_id', 'product_id', 'price_unit', 'price_box', 'box_qty', 'unit_qty', 'payment_type', 'cash_amount', 'debt_amount', 'comment']);
        $this->payment_type = 'cash';
        $this->payment_method = 'cash';
        $this->price_unit = 0;
        $this->price_box = 0;
        $this->box_qty = 0;
        $this->unit_qty = 0;
        $this->cash_amount = 0;
        $this->debt_amount = 0;
        $this->comment = '';
    }

    public function updated($name, $value): void
    {
        $floatFields = ['price_unit', 'price_box', 'cash_amount'];
        if (in_array($name, $floatFields, true) && is_string($value)) {
            $this->$name = $this->toFloat($value);
            return;
        }
    }

    protected function toFloat($value): float
    {
        if (is_string($value)) {
            $value = str_replace(',', '.', $value);
        }
        return is_numeric($value) ? (float) $value : 0.0;
    }

    protected function sanitizeNumericFields(): void
    {
        $this->price_unit = $this->toFloat($this->price_unit);
        $this->price_box = $this->toFloat($this->price_box);
        $this->cash_amount = $this->toFloat($this->cash_amount);
        $this->debt_amount = $this->toFloat($this->debt_amount);
    }

    protected function unitsPerBox(): int
    {
        $product = $this->product_id ? Product::find($this->product_id) : null;
        return max(1, $product->units_per_box ?? 1);
    }

    protected function calculateTotals(): array
    {
        $unitsPerBox = $this->unitsPerBox();
        $boxQty = (int) ($this->box_qty ?: 0);
        $unitQty = (int) ($this->unit_qty ?: 0);

        if ($boxQty > 0 && $unitQty === $boxQty * $unitsPerBox) {
            $unitQty = 0;
        }

        if ($unitQty <= 0 && $boxQty > 0) {
            $unitQty = $boxQty * $unitsPerBox;
        }
        if ($boxQty <= 0 && $unitQty > 0) {
            $boxQty = (int) ceil($unitQty / $unitsPerBox);
        }

        $unitPrice = $this->price_unit > 0 ? $this->toFloat($this->price_unit) : (($this->price_box ?? 0) > 0 && $unitsPerBox > 0 ? $this->toFloat($this->price_box) / $unitsPerBox : 0);
        $boxPrice = $this->price_box > 0 ? $this->toFloat($this->price_box) : $unitPrice * $unitsPerBox;

        $totalUnits = ($boxQty * $unitsPerBox) + $unitQty;
        $totalPrice = $unitPrice * $totalUnits;

        return compact('unitsPerBox', 'boxQty', 'unitQty', 'unitPrice', 'boxPrice', 'totalUnits', 'totalPrice');
    }

    protected function syncDebtFromCash(): void
    {
        if ($this->syncing) {
            return;
        }
        $this->syncing = true;

        $totals = $this->calculateTotals();
        $totalPrice = $totals['totalPrice'];

        if ($this->payment_type === 'cash') {
            $this->cash_amount = $totalPrice;
            $this->debt_amount = 0;
        } elseif ($this->payment_type === 'debt') {
            $this->cash_amount = 0;
            $this->debt_amount = $totalPrice;
        } else { // mixed
            $cash = max(0, $this->cash_amount ?? 0);
            $cash = min($cash, $totalPrice);
            $this->cash_amount = $cash;
            $this->debt_amount = max(0, $totalPrice - $cash);
        }

        $this->syncing = false;
    }

    public function updatedBoxQty($value): void
    {
        if ($this->syncing) {
            return;
        }
        $this->syncing = true;
        $unitsPerBox = $this->unitsPerBox();
        $this->unit_qty = max(0, (int) $value * $unitsPerBox);
        if ($this->price_box > 0 && $unitsPerBox > 0) {
            $this->price_unit = $this->price_box / $unitsPerBox;
        }
        $this->syncing = false;
        $this->syncDebtFromCash();
    }

    public function updatedUnitQty($value): void
    {
        if ($this->syncing) {
            return;
        }
        $this->syncing = true;
        $unitsPerBox = $this->unitsPerBox();
        $this->box_qty = max(0, (int) ceil(($value ?: 0) / $unitsPerBox));
        if ($this->price_unit > 0 && $unitsPerBox > 0) {
            $this->price_box = $this->price_unit * $unitsPerBox;
        }
        $this->syncing = false;
        $this->syncDebtFromCash();
    }

    public function updatedPriceBox($value): void
    {
        if ($this->syncing) {
            return;
        }
        $this->syncing = true;
        $unitsPerBox = $this->unitsPerBox();
        $price = $this->toFloat($value);
        if ($price > 0 && $unitsPerBox > 0) {
            $this->price_unit = $price / $unitsPerBox;
        }
        $this->syncing = false;
        $this->syncDebtFromCash();
    }

    public function updatedPriceUnit($value): void
    {
        if ($this->syncing) {
            return;
        }
        $this->syncing = true;
        $unitsPerBox = $this->unitsPerBox();
        $price = $this->toFloat($value);
        if ($price > 0 && $unitsPerBox > 0) {
            $this->price_box = $price * $unitsPerBox;
        }
        $this->syncing = false;
        $this->syncDebtFromCash();
    }

    public function updatedCashAmount(): void
    {
        $this->syncDebtFromCash();
    }

    public function updatedPaymentType(): void
    {
        $this->syncDebtFromCash();
    }

    protected function flashQuantityError(string $message): void
    {
        $this->addError('quantity', $message);
    }

    #[Layout('components.layouts.dashboard')]
    public function render()
    {
        $clients = Client::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('livewire.sales.create', [
            'clients' => $clients,
            'products' => $products,
        ]);
    }
}
