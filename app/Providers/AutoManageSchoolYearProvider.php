<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\AutoManageSchoolYear;

class AutoManageSchoolYearProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Register the AutoManageSchoolYear command
        $this->commands([
            AutoManageSchoolYear::class,
        ]);

        // Optionally run the command when the app starts
        Artisan::call('schoolyear:auto-manage');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
