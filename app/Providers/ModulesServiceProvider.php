<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ModulesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // For each of the registered modules, include their routes and Views
        $modules = config("module.modules");

        if (!empty($modules)) {
            // while (list(,$module) = each($modules)) {
            foreach ($modules as $key => $module) {
                // Load the routes for each of the modules
                if(file_exists(app_path().'/Modules/'.$module.'/routes.php')) {
                    include app_path().'/Modules/'.$module.'/routes.php';
                }

                // Load the views
                if(is_dir(app_path().'/Modules/'.$module.'/Views')) {
                    $this->loadViewsFrom(app_path().'/Modules/'.$module.'/Views', $module);
                }
            }
        }
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
