<?php

namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{

    protected $middleware = [
        'csrf'  => \App\Http\Middleware\VerifyCsrfToken::class
        ];
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        //
        $router->pattern('id', '[0-9]+');

        parent::boot($router);

        //model binding
        $router->bind('owned_website', function($value) {
            $found = null;

            if (\Auth::check())
                $found = \App\Models\Website::where('user_id', \Auth::user()->id)->first();

            if (empty($found))
                \App::abort(404, 'Website not found');
            else
                return $found;
        });
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function ($router) {
            require app_path('Http/routes.php');
        });
    }
}
