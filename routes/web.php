<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;
use App\Livewire\Dashboard\Home;
use App\Livewire\Categories\Index as CategoriesIndex;
use App\Livewire\Products\Index as ProductsIndex;
use App\Livewire\Purchases\Index as PurchasesIndex;
use App\Livewire\Firms\Index as FirmsIndex;
use App\Livewire\Clients\Index as ClientsIndex;
use App\Livewire\Dashboard\Home as DashboardHome;
use Illuminate\Support\Facades\Auth;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\PurchaseDocument;
use App\Models\Sale;
use App\Models\ClientPayment;
use App\Models\PurchaseReceipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\SettingsController;


Route::get('/login', Login::class)->name('login');

Route::middleware('auth')->group(function () {
    Route::get('/', DashboardHome::class)->name('dashboard');
    Route::get('/categories', CategoriesIndex::class)->name('categories');

    // Placeholder routes for navigation
    Route::get('/products', ProductsIndex::class)->name('products');
    Route::get('/products/{product}', function (Product $product) {
        $product->load(['category', 'purchases.firm', 'purchases.receipts']);
        $purchases = $product->purchases()->with(['firm', 'receipts'])->latest()->get();

        return view('products.show', [
            'product' => $product,
            'purchases' => $purchases,
        ]);
    })->name('products.show');
    Route::get('/purchases', PurchasesIndex::class)->name('purchases');
    Route::get('/purchases/{purchase}', function (Purchase $purchase) {
        $purchase->load(['product.category', 'firm', 'documents']);
        return view('purchases.show', ['purchase' => $purchase]);
    })->name('purchases.show');

    Route::post('/purchases/{purchase}/documents', function (Request $request, Purchase $purchase) {
        $data = $request->validate([
            'files.*' => ['required', 'file', 'max:5120'],
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $stored = $file->store('purchase-docs', 'public');
                PurchaseDocument::create([
                    'purchase_id' => $purchase->id,
                    'path' => Storage::url($stored),
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        return back()->with('status', __('Документы загружены'));
    })->name('purchases.documents.store');

    Route::post('/purchases/{purchase}/receipts', function (Request $request, Purchase $purchase) {
        $data = $request->validate([
            'box_qty' => ['required', 'integer', 'min:1'],
        ]);

        PurchaseReceipt::create([
            'purchase_id' => $purchase->id,
            'box_qty' => $data['box_qty'],
            'user_id' => Auth::id(),
        ]);

        $purchase->load('product');
        if ($purchase->product && $data['box_qty'] > 0) {
            $units = max(0, $purchase->product->units_per_box ?? 0);
            $purchase->product->box_count = ($purchase->product->box_count ?? 0) + $data['box_qty'];
            $purchase->product->quantity = ($purchase->product->quantity ?? 0) + ($data['box_qty'] * $units);
            $purchase->product->save();
        }

        $purchase->received_box_qty = ($purchase->received_box_qty ?? 0) + $data['box_qty'];
        $purchase->save();

        return back()->with('status', __('Поставка добавлена'));
    })->name('purchases.receipts.store');

    Route::get('/clients', ClientsIndex::class)->name('clients');
    Route::get('/clients/{client}', function (\App\Models\Client $client) {
        $client->load(['payments', 'sales.product']);
        return view('clients.show', [
            'client' => $client,
            'payments' => $client->payments()->latest()->get(),
            'sales' => $client->sales()->with('product')->latest()->get(),
        ]);
    })->name('clients.show');

    Route::get('/sales', \App\Livewire\Sales\Index::class)->name('sales');
    Route::get('/cashdesk', \App\Livewire\Sales\Create::class)->name('cashdesk');
    Route::get('/sales/{sale}', function (Sale $sale) {
        $sale->load(['product.category', 'client']);
        return view('sales.show', ['sale' => $sale]);
    })->name('sales.show');
    Route::get('/expenses', \App\Livewire\Expenses\Index::class)->name('expenses');
    Route::get('/bank', \App\Livewire\Bank\Index::class)->name('bank');
    Route::view('/debts', 'welcome')->name('debts');
    Route::get('/firms', FirmsIndex::class)->name('firms');
    Route::get('/users', \App\Livewire\Users\Index::class)->name('users');
    Route::get('/firms/{firm}', function (\App\Models\Firm $firm) {
        $firm->load(['payments', 'purchases.product']);
        return view('firms.show', [
            'firm' => $firm,
            'payments' => $firm->payments()->latest()->get(),
            'purchases' => $firm->purchases()->with('product')->latest()->get(),
        ]);
    })->name('firms.show');
    Route::view('/companies', 'welcome')->name('companies');
    Route::view('/arrears', 'welcome')->name('arrears');

    Route::get('/settings', function () {
        $user = Auth::user();
        return view('settings.index', ['user' => $user]);
    })->name('settings');

    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

    Route::get('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');

});
