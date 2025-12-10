<?php

namespace App\Livewire\Dashboard;

use App\Models\BankTransaction;
use App\Models\Client;
use App\Models\ClientPayment;
use App\Models\FirmPayment;
use App\Models\Purchase;
use App\Models\Sale;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Home extends Component
{
    protected array $costCache = [];

    #[Layout('components.layouts.dashboard')]
    public function render()
    {
        $timeframes = [
            'week' => [
                'label' => __('Неделя'),
                'start' => Carbon::now()->startOfDay()->subDays(6),
            ],
            'month' => [
                'label' => __('Месяц'),
                'start' => Carbon::now()->startOfDay()->subDays(29),
            ],
            'year' => [
                'label' => __('Год'),
                'start' => Carbon::now()->startOfDay()->subYear(),
            ],
        ];

        $labels = collect($timeframes)->mapWithKeys(fn ($v, $k) => [$k => $v['label']])->all();
        $data = [];
        foreach ($timeframes as $key => $tf) {
            $data[$key] = $this->collectMetrics($tf['start'], Carbon::now());
        }
        $today = $this->collectMetrics(Carbon::now()->startOfDay(), Carbon::now());
        $profitSeries = $this->profitSeries(7);

        return view('livewire.dashboard.home', [
            'timeframes' => $labels,
            'data' => $data,
            'today' => $today,
            'profitSeries' => $profitSeries,
        ]);
    }

    protected function collectMetrics(Carbon $from, Carbon $to): array
    {
        $sales = Sale::whereBetween('created_at', [$from, $to]);
        $purchases = Purchase::whereBetween('created_at', [$from, $to]);
        $expenses = BankTransaction::where('type', 'expense')->whereBetween('created_at', [$from, $to]);
        $deposits = BankTransaction::whereIn('type', ['deposit', 'client_payment'])->whereBetween('created_at', [$from, $to]);

        $revenue = (float) $sales->clone()->sum('total_price');
        $salesCount = (int) $sales->clone()->count();
        $unitsSold = (int) $sales->clone()->sum('total_units');
        $profit = $this->profitForPeriod($from, $to);
        $purchasesSum = (float) $purchases->clone()->sum(DB::raw('(purchase_price * COALESCE(box_qty,1)) + COALESCE(delivery_cn,0) + COALESCE(delivery_tj,0)'));
        $deliverySum = (float) $purchases->clone()->sum(DB::raw('COALESCE(delivery_cn,0) + COALESCE(delivery_tj,0)'));
        $expensesSum = (float) $expenses->sum('amount');
        $depositsSum = (float) $deposits->sum('amount');

        $net = $revenue - $purchasesSum - $expensesSum;

        $debtPaid = (float) ClientPayment::whereBetween('created_at', [$from, $to])
            ->where('method', '!=', 'debt')
            ->sum('amount');

        $debtTakenClients = (float) ClientPayment::whereBetween('created_at', [$from, $to])
            ->where('method', 'debt')
            ->sum('amount');

        $debtTakenFirms = (float) FirmPayment::whereBetween('created_at', [$from, $to])
            ->where('method', 'debt')
            ->sum('amount');

        $topProducts = Sale::whereBetween('created_at', [$from, $to])
            ->select('product_id', DB::raw('SUM(total_price) as sum'), DB::raw('SUM(total_units) as qty'))
            ->with('product:id,name')
            ->groupBy('product_id')
            ->orderByDesc('sum')
            ->limit(3)
            ->get()
            ->map(fn($row) => [
                'name' => $row->product?->name ?? __('Без названия'),
                'sum' => (float) $row->sum,
                'qty' => (int) $row->qty,
            ])
            ->all();

        $topClients = Sale::whereBetween('created_at', [$from, $to])
            ->select('client_id', DB::raw('SUM(total_price) as sum'))
            ->with('client:id,name,debt')
            ->groupBy('client_id')
            ->orderByDesc('sum')
            ->limit(3)
            ->get()
            ->map(fn($row) => [
                'name' => $row->client?->name ?? __('Неизвестно'),
                'sum' => (float) $row->sum,
                'debt' => (float) ($row->client?->debt ?? 0),
            ])
            ->all();

        return [
            'revenue' => $revenue,
            'purchases' => $purchasesSum,
            'delivery' => $deliverySum,
            'salesCount' => $salesCount,
            'unitsSold' => $unitsSold,
            'profit' => $profit,
            'expenses' => $expensesSum,
            'deposits' => $depositsSum,
            'net' => $net,
            'debtPaid' => $debtPaid,
            'debtTaken' => $debtTakenClients + $debtTakenFirms,
            'chart' => $this->buildChart($sales->clone(), $from, $to),
            'topProducts' => $topProducts,
            'topClients' => $topClients,
        ];
    }

    protected function buildChart($salesQuery, Carbon $from, Carbon $to): array
    {
        $days = $from->diffInDays($to) + 1;
        $points = min(10, $days);
        $interval = max(1, (int) ceil($days / $points));

        $chart = [];
        $cursor = $from->copy();
        while ($cursor <= $to) {
            $end = $cursor->copy()->addDays($interval - 1)->endOfDay();
            $sum = (float) $salesQuery->clone()
                ->whereBetween('created_at', [$cursor, $end])
                ->sum('total_price');
            $chart[] = $sum;
            $cursor = $cursor->addDays($interval);
        }

        // Normalize to 100 max for display
        $max = max($chart) ?: 1;
        return array_map(fn($v) => round(($v / $max) * 100), $chart);
    }

    protected function profitForPeriod(Carbon $from, Carbon $to): float
    {
        $sales = Sale::with('product.purchases')
            ->whereBetween('created_at', [$from, $to])
            ->get();

        $profit = 0.0;
        foreach ($sales as $sale) {
            $cost = $this->costPerUnit($sale->product_id);
            $profit += ($sale->total_price ?? 0) - $cost * ($sale->total_units ?? 0);
        }
        return $profit;
    }

    protected function costPerUnit(?int $productId): float
    {
        if (!$productId) {
            return 0.0;
        }
        if (array_key_exists($productId, $this->costCache)) {
            return $this->costCache[$productId];
        }
        $cost = Purchase::where('product_id', $productId)
            ->orderByDesc('created_at')
            ->value('cost_per_unit') ?? 0;
        $this->costCache[$productId] = (float) $cost;
        return (float) $cost;
    }

    protected function profitSeries(int $days): array
    {
        $series = [];
        $cursor = Carbon::now()->startOfDay()->subDays($days - 1);
        for ($i = 0; $i < $days; $i++) {
            $from = $cursor->copy();
            $to = $cursor->copy()->endOfDay();
            $series[] = [
                'label' => $from->format('d.m'),
                'value' => $this->profitForPeriod($from, $to),
            ];
            $cursor->addDay();
        }
        return $series;
    }
}
