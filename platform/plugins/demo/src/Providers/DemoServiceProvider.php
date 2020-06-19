<?php

namespace Botble\Demo\Providers;

use Botble\Demo\Models\Demo;
use Illuminate\Support\ServiceProvider;
use Botble\Demo\Repositories\Caches\DemoCacheDecorator;
use Botble\Demo\Repositories\Eloquent\DemoRepository;
use Botble\Demo\Repositories\Interfaces\DemoInterface;
use Botble\Base\Supports\Helper;
use Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;

class DemoServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(DemoInterface::class, function () {
            return new DemoCacheDecorator(new DemoRepository(new Demo));
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    public function boot()
    {
        $this->setNamespace('plugins/demo')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadRoutes(['web']);

        Event::listen(RouteMatched::class, function () {
            if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
                \Language::registerModule([Demo::class]);
            }

            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-demo',
                'priority'    => 5,
                'parent_id'   => null,
                'name'        => 'plugins/demo::demo.name',
                'icon'        => 'fa fa-list',
                'url'         => route('demo.index'),
                'permissions' => ['demo.index'],
            ]);
        });
    }
}
