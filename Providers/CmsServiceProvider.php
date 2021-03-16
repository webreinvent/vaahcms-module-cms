<?php namespace VaahCms\Modules\Cms\Providers;


use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use VaahCms\Modules\Cms\Http\Middleware\SetContent;
use VaahCms\Modules\Cms\Http\Middleware\SetContentType;
use VaahCms\Modules\Cms\Providers\RouteServiceProvider;
use VaahCms\Modules\Cms\Providers\EventServiceProvider;
use VaahCms\Modules\Cms\View\Components\Example;

class CmsServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot(Router $router)
    {


        $this->registerMiddleware($router);
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerAssets();

        //$this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->registerSeeders();
        $this->registerBladeDirectives();
        $this->registerBladeComponents();
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $this->registerHelpers();
        $this->registerLibraries();
    }

    /**
     *
     */
    private function registerMiddleware($router) {

        //register middleware
        $router->aliasMiddleware('set.content.type', SetContentType::class);
        $router->aliasMiddleware('set.content', SetContent::class);

    }

    /**
     *
     */
    private function registerHelpers() {

        //load all the helpers
        foreach (glob(__DIR__.'/../Helpers/*.php') as $filename){
            require_once($filename);
        }

    }

    /**
     *
     */
    private function registerLibraries()
    {
        //load all the helpers
        foreach (glob(__DIR__.'/Libraries/*.php') as $filename){
            require_once($filename);
        }
    }


    /**
     *
     */
    private function registerSeeders() {

        //load all the seeds
        foreach (glob(__DIR__.'/../Database/Seeds/*.php') as $filename){
            require_once($filename);
        }

    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('cms.php'),
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'cms'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('/views/vaahcms/modules/cms');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/views/vaahcms/modules/cms';
        }, \Config::get('view.paths')), [$sourcePath]), 'cms');

    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerAssets()
    {

        $sourcePath = __DIR__.'/../Resources/assets';

        $desPath = public_path('vaahcms/modules/cms/assets');

        $this->publishes([
            $sourcePath => $desPath
        ],'assets');


    }


    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('/lang/vaahcms/modules/cms');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'cms');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'cms');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function registerBladeDirectives()
    {


        \Blade::directive('contents', function ($expression) {
            $params = explode(',', $expression);

            echo "<pre>";
            print_r($params);
            echo "</pre>";

            \Blade::directive('content', function ($expression) use ($params) {
                return $params[0];
            });


            return "<?php echo 'Hello ' . {$expression}; ?>";
        });





    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function registerBladeComponents()
    {

        \Blade::component('exp', Example::class);

    }
}
