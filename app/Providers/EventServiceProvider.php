<?php

namespace App\Providers;

use App\Models\Merchant;
use App\Models\Outlet;
use App\Models\Transaction;
use App\Models\User;
use App\Observers\MerchantObserver;
use App\Observers\OutletObserver;
use App\Observers\TransactionObserver;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        Merchant::observe(MerchantObserver::class);
        Outlet::observe(OutletObserver::class);
        Transaction::observe(TransactionObserver::class);
    }
}
