<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\AuditDashboardController;
use App\Http\Controllers\GlobalSearchController;
use App\Http\Controllers\RiskFlagController;
use App\Http\Controllers\ApprovalRequestController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\RetailCopilotController;
use App\Http\Controllers\EntityLinkController;
use App\Http\Controllers\ScenarioRuleController;
use App\Http\Controllers\InvestigationController;
use App\Http\Controllers\RuleAnalyticsController;

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

Route::post('/logout', function () {

    Auth::logout();

    request()->session()->invalidate();

    request()->session()->regenerateToken();

    return redirect('/login');

})->name('logout');

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::middleware([
        'auth'
        ])
        
        ->group(function(){

            Route::get(

                '/notifications',
                
                [NotificationController::class,'index']
                
                );
        
        Route::middleware(
        ['admin']
        )
        
        ->group(function(){
        
        Route::resource(
        'risk-flags',
        RiskFlagController::class
        );
        
        Route::resource(
        'investigation',
        InvestigationController::class
        );
        
        Route::resource(
        'entity-links',
        EntityLinkController::class
        );
        
        Route::resource(
        'scenario-rules',
        ScenarioRuleController::class
        );
        
        Route::get(
        '/analytics/rule-performance',
        [RuleAnalyticsController::class,'index']
        );
        
        });
        
        });

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/', function (Illuminate\Http\Request $request) {

        if(auth()->user()->role == 'staff') {
    
            return redirect('/orders');
    
        }
    
        return app(
            \App\Http\Controllers\DashboardController::class
        )->index($request);
    
    })->name('dashboard');


    Route::get(
        '/audit-dashboard',
        [AuditDashboardController::class, 'index']
    )->name('audit-dashboard.index');

    Route::get(
        '/global-search',
        [GlobalSearchController::class, 'search']
    )->name('global-search');
    

    Route::get(
        '/approvals',
        [ApprovalRequestController::class, 'index']
    )->name('approvals.index');
    
    Route::post(
        '/approvals/{id}/approve',
        [ApprovalRequestController::class, 'approve']
    )->name('approvals.approve');
    
    Route::post(
        '/approvals/{id}/reject',
        [ApprovalRequestController::class, 'reject']
    )->name('approvals.reject');

    Route::post(
        '/retail-copilot/ask',
        [RetailCopilotController::class, 'ask']
    )->name('retail-copilot.ask');

    Route::get(
        '/entity-links',
        [EntityLinkController::class,'index']
        );
  
        /*
    |--------------------------------------------------------------------------
    | RISK FLAG SCAN
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/risk-flags',
        [RiskFlagController::class, 'index']
    )->name('risk-flags.index');

    Route::post(
        '/risk-flags/scan',
        [RiskFlagController::class, 'scan']
    )->name('risk-flags.scan');
    
    Route::post(
        '/risk-flags/scan-dynamic-pattern',
        [RiskFlagController::class, 'scanDynamicPattern']
    )->name('risk-flags.scan-dynamic-pattern');
    
    Route::get(
        '/risk-flags/{id}',
        [RiskFlagController::class,'show']
        );
        
    Route::post(
        '/risk-flags/{id}/close',
        [RiskFlagController::class, 'close']
    )->name('risk-flags.close');
    
    Route::post(
        '/risk-flags/{id}/reopen',
        [RiskFlagController::class, 'reopen']
    )->name('risk-flags.reopen');


        /*
    |--------------------------------------------------------------------------
    | RULES CREATION MODELING
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/scenario-rules',
        [ScenarioRuleController::class, 'index']
    )->name('scenario-rules.index');
    
    Route::get(
        '/scenario-rules/create',
        [ScenarioRuleController::class, 'create']
    )->name('scenario-rules.create');
    
    Route::post(
        '/scenario-rules',
        [ScenarioRuleController::class, 'store']
    )->name('scenario-rules.store');
    
    Route::get(
        '/scenario-rules/{id}/edit',
        [ScenarioRuleController::class, 'edit']
    )->name('scenario-rules.edit');
    
    Route::put(
        '/scenario-rules/{id}',
        [ScenarioRuleController::class, 'update']
    )->name('scenario-rules.update');
    
    Route::delete(
        '/scenario-rules/{id}',
        [ScenarioRuleController::class, 'destroy']
    )->name('scenario-rules.destroy');
    
    Route::post(
        '/scenario-rules/run',
        [ScenarioRuleController::class, 'run']
    )->name('scenario-rules.run');

    Route::get(
        '/analytics/rule-performance',
        [RuleAnalyticsController::class,'index']
        );

        /*
    |--------------------------------------------------------------------------
    | Investigation Controller
    |--------------------------------------------------------------------------
    */


    Route::middleware('auth')
->group(function(){

Route::get(
'/investigation',
[InvestigationController::class,'index']
);

Route::get(
'/investigation/{id}',
[InvestigationController::class,'show']
);

Route::post(
'/investigation/{id}/assign',
[InvestigationController::class,'assign']
);

Route::post(
'/investigation/{id}/status',
[InvestigationController::class,'updateStatus']
);

});

    /*
    |--------------------------------------------------------------------------
    | Products
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'products',
        ProductController::class
    );

    Route::get(
        '/products-live-search',
        [ProductController::class, 'liveSearch']
    );

    /*
    |--------------------------------------------------------------------------
    | Orders
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'orders',
        OrderController::class
    );

    Route::get(
        '/orders-live-search',
        [OrderController::class, 'liveSearch']
    );

    /*
    |--------------------------------------------------------------------------
    | Customers
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/customers/run-segmentation',
        [CustomerController::class,'runSegmentation']
    );
    
    Route::post(
        '/customers/run-risk-score',
        [CustomerController::class, 'runRiskScore']
    )->name('customers.run-risk-score');
    
    Route::resource(
        'customers',
        CustomerController::class
    );

    Route::get(
        '/customers-live-search',
        [CustomerController::class, 'liveSearch']
    );

    Route::get(
        '/analytics/order-heatmap',
        [AnalyticsController::class, 'orderHeatmap']
    )->name('analytics.order-heatmap');

    Route::get(
        '/analytics/store-performance',
        [AnalyticsController::class, 'storePerformance']
    )->name('analytics.store-performance');

    Route::get(
        '/analytics/inventory-forecast',
        [AnalyticsController::class, 'inventoryForecast']
    )->name('analytics.inventory-forecast');

    /*
    |--------------------------------------------------------------------------
    | Stocks
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/stocks',
        [StockController::class, 'index']
    )->name('stocks.index');

    Route::get(
        '/stocks/{store_id}/{product_id}/edit',
        [StockController::class, 'edit']
    )->name('stocks.edit');

    Route::post(
        '/stocks/update',
        [StockController::class, 'update']
    )->name('stocks.update');

    Route::get(
        '/stocks-live-search',
        [StockController::class, 'liveSearch']
    );

    Route::get(
        '/stock-movements',
        [StockMovementController::class, 'index']
    )->name('stock-movements.index');

    /*
    |--------------------------------------------------------------------------
    | Payments
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/orders/{id}/pay',
        [PaymentController::class, 'pay']
    )->name('orders.pay');

    Route::post(
        '/orders/{id}/cancel',
        [PaymentController::class, 'cancel']
    )->name('orders.cancel');

    Route::post(
        '/orders/{id}/ship',
        [PaymentController::class, 'ship']
    )->name('orders.ship');
    
    Route::post(
        '/orders/{id}/complete',
        [PaymentController::class, 'complete']
    )->name('orders.complete');

    Route::get(
        '/orders-export',
        [OrderController::class, 'exportCsv']
    );

    /*
    |--------------------------------------------------------------------------
    | Activity Log
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/activity-logs',
        [ActivityLogController::class, 'index']
    )->name('activity.logs');

    /*
    |--------------------------------------------------------------------------
    | Invoice
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/orders/{id}/invoice',
        [InvoiceController::class, 'generate']
    )->name('orders.invoice');


    Route::get(
        '/orders/{id}/invoice-preview',
        [InvoiceController::class, 'preview']
    )->name('orders.invoice.preview');
    

    /*
    |--------------------------------------------------------------------------
    | Users Management
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/users-live-search',
        [UserController::class, 'liveSearch']
    );

    Route::middleware(['admin'])->group(function () {

        Route::resource(
            'users',
            UserController::class
        );

        Route::get(
            '/settings',
            [SettingController::class, 'index']
        )->name('settings.index');
        
        Route::put(
            '/settings',
            [SettingController::class, 'update']
        )->name('settings.update');
    
    });
});
/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::get(
    '/notifications',
    [NotificationController::class, 'index']
)->name('notifications.index');

require __DIR__.'/auth.php';