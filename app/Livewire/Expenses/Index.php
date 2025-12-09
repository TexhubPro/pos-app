<?php

namespace App\Livewire\Expenses;

use App\Models\BankTransaction;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{
    public $amount = 0;
    public string $source = '';
    public string $method = 'cash';
    public string $comment = '';
    public bool $showModal = false;

    protected function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:0.01'],
            'source' => ['required', 'string', 'max:255'],
            'method' => ['required', 'string', 'in:cash,card_milli,dushanbe_city,alif'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function submit(): void
    {
        $data = $this->validate();

        BankTransaction::create([
            'type' => 'expense',
            'amount' => $data['amount'],
            'source' => $data['source'],
            'method' => $data['method'],
            'comment' => $data['comment'] ?: null,
        ]);

        $this->amount = 0;
        $this->source = '';
        $this->method = 'cash';
        $this->comment = '';
        $this->showModal = false;
        session()->flash('status', __('Расход сохранён и учтён в банке'));
    }

    #[Layout('components.layouts.dashboard')]
    public function render()
    {
        $expenses = BankTransaction::where('type', 'expense')->latest()->take(30)->get();

        return view('livewire.expenses.index', [
            'expenses' => $expenses,
        ]);
    }
}
