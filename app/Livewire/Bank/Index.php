<?php

namespace App\Livewire\Bank;

use App\Models\BankTransaction;
use App\Models\Client;
use App\Models\ClientPayment;
use App\Models\Firm;
use App\Models\FirmPayment;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{
    public $deposit_amount = 0;
    public string $deposit_source = 'Салмон';

    public $withdraw_amount = 0;
    public string $withdraw_comment = '';

    public $client_id = '';
    public $client_amount = 0;
    public string $client_method = 'cash';
    public string $client_comment = '';

    public $firm_id = '';
    public $firm_amount = 0;
    public string $firm_method = 'cash';
    public string $firm_comment = '';

    public bool $showDepositModal = false;
    public bool $showWithdrawModal = false;
    public bool $showClientModal = false;
    public bool $showFirmModal = false;

    public function getBalanceProperty(): float
    {
        $plus = BankTransaction::whereIn('type', ['deposit', 'client_payment'])->sum('amount');
        $minus = BankTransaction::whereIn('type', ['withdraw', 'firm_payment', 'expense'])->sum('amount');
        return (float) ($plus - $minus);
    }

    public function getFirmDebtProperty(): float
    {
        return (float) (Firm::sum('debt') ?? 0);
    }

    public function getClientDebtProperty(): float
    {
        return (float) (Client::sum('debt') ?? 0);
    }

    public function getTotalProfitProperty(): float
    {
        // Прибыль = выручка - (себестоимость * проданные единицы)
        $sales = Sale::with('product')->get();
        $profit = 0.0;
        foreach ($sales as $sale) {
            $costPerUnit = (float) ($sale->product?->purchases()->latest()->value('cost_per_unit') ?? 0);
            $profit += ($sale->total_price ?? 0) - $costPerUnit * ($sale->total_units ?? 0);
        }
        return $profit;
    }

    public function addDeposit(): void
    {
        $data = $this->validate([
            'deposit_amount' => ['required', 'numeric', 'min:0.01'],
            'deposit_source' => ['required', 'string', 'max:255'],
        ]);

        BankTransaction::create([
            'type' => 'deposit',
            'source' => $data['deposit_source'],
            'amount' => $data['deposit_amount'],
        ]);

        $this->deposit_amount = 0;
        $this->showDepositModal = false;
        session()->flash('status', __('Вклад добавлен'));
    }

    public function addWithdraw(): void
    {
        $data = $this->validate([
            'withdraw_amount' => ['required', 'numeric', 'min:0.01'],
            'withdraw_comment' => ['nullable', 'string', 'max:1000'],
        ]);

        BankTransaction::create([
            'type' => 'withdraw',
            'amount' => $data['withdraw_amount'],
            'comment' => $data['withdraw_comment'] ?: null,
        ]);

        $this->withdraw_amount = 0;
        $this->withdraw_comment = '';
        $this->showWithdrawModal = false;
        session()->flash('status', __('Снятие учтено'));
    }

    public function addClientPayment(): void
    {
        $data = $this->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'client_amount' => ['required', 'numeric', 'min:0.01'],
            'client_method' => ['required', 'string', 'in:cash,card_milli,dushanbe_city,alif'],
            'client_comment' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($data) {
            $client = Client::lockForUpdate()->find($data['client_id']);
            if (!$client) {
                return;
            }

            $payment = ClientPayment::create([
                'client_id' => $client->id,
                'amount' => $data['client_amount'],
                'method' => $data['client_method'],
                'comment' => $data['client_comment'] ?? null,
            ]);

            $client->debt = max(0, ($client->debt ?? 0) - $payment->amount);
            $client->save();

            BankTransaction::create([
                'type' => 'client_payment',
                'client_id' => $client->id,
                'amount' => $payment->amount,
                'method' => $payment->method,
                'comment' => $payment->comment,
            ]);
        });

        $this->client_id = '';
        $this->client_amount = 0;
        $this->client_method = 'cash';
        $this->client_comment = '';
        $this->showClientModal = false;
        session()->flash('status', __('Оплата клиента добавлена'));
    }

    public function addFirmPayment(): void
    {
        $data = $this->validate([
            'firm_id' => ['required', 'exists:firms,id'],
            'firm_amount' => ['required', 'numeric', 'min:0.01'],
            'firm_method' => ['required', 'string', 'in:cash,card_milli,dushanbe_city,alif'],
            'firm_comment' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($data) {
            $firm = Firm::lockForUpdate()->find($data['firm_id']);
            if (!$firm) {
                return;
            }

            $payment = FirmPayment::create([
                'firm_id' => $firm->id,
                'amount' => $data['firm_amount'],
                'method' => $data['firm_method'],
                'comment' => $data['firm_comment'] ?? null,
            ]);

            $firm->debt = max(0, ($firm->debt ?? 0) - $payment->amount);
            $firm->save();

            BankTransaction::create([
                'type' => 'firm_payment',
                'firm_id' => $firm->id,
                'amount' => $payment->amount,
                'method' => $payment->method,
                'comment' => $payment->comment,
            ]);
        });

        $this->firm_id = '';
        $this->firm_amount = 0;
        $this->firm_method = 'cash';
        $this->firm_comment = '';
        $this->showFirmModal = false;
        session()->flash('status', __('Оплата по фирме учтена'));
    }

    #[Layout('components.layouts.dashboard')]
    public function render()
    {
        $transactions = BankTransaction::latest()->take(10)->get();
        return view('livewire.bank.index', [
            'transactions' => $transactions,
            'clients' => Client::orderBy('name')->get(),
            'firms' => Firm::orderBy('name')->get(),
        ]);
    }
}
