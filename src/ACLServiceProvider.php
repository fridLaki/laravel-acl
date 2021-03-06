<?php

namespace Junges\ACL;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\ServiceProvider;
use Junges\ACL\Console\Commands\CreateGroup;
use Junges\ACL\Console\Commands\CreatePermission;
use Junges\ACL\Console\Commands\ShowPermissions;
use Junges\ACL\Console\Commands\UserPermissions;
use Junges\ACL\Http\Observers\GroupObserver;

class ACLServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @param Dispatcher $events
     * @param Repository $config
     * @param Factory $view
     * @return void
     */
    public function boot(Dispatcher $events, Repository $config, Factory $view)
    {

        //Publishes migrations:
        $this->loadMigrations();

        //Publishes config
        $this->publishConfig();

        //Publishes views
        $this->loadViews();

        //Load commands
        $this->loadCommands();

        //Load translations
        $this->loadTranslations();

        //Load observers
        $this->loadObservers();
    }


    /**
     * Load and publishes the views folder
     */
    public function loadViews()
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'acl');
        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('views/vendor/junges/acl'),
        ], 'views');
    }

    /**
     * Load and publishes the pt-br.php configuration file
     */
    public function publishConfig()
    {
        $this->publishes([
            __DIR__ . '/../config/acl.php' => config_path('acl.php'),
        ], 'config');
    }

    public function loadCommands()
    {
        if ($this->app->runningInConsole())
            $this->commands([
                CreatePermission::class,
                ShowPermissions::class,
                CreateGroup::class,
                UserPermissions::class
            ]);
    }

    public function loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ .'/database/migrations');
        $this->publishes([
            __DIR__ .'/database/migrations' => database_path('migrations/vendor/junges/acl'),
        ], 'migrations');
    }

    public function loadTranslations()
    {
        $translationsPath = __DIR__.'/resources/lang';
        $this->loadTranslationsFrom($translationsPath, 'acl');
        $this->publishes([
            $translationsPath => base_path('resources/lang/vendor/acl')
        ], 'translations');
    }

    public function loadObservers()
    {
//        $groupModel = app(config('acl.models.group'));
//        $groupModel->observe(GroupObserver::class);
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
