<?php

namespace Botble\Demo\Providers;

use Botble\Base\Supports\Helper;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Demo\Models\Category;
use Botble\Demo\Models\Demo;
use Botble\Demo\Repositories\Caches\CategoryCacheDecorator;
use Botble\Demo\Repositories\Caches\DemoCacheDecorator;
use Botble\Demo\Repositories\Eloquent\CategoryRepository;
use Botble\Demo\Repositories\Eloquent\DemoRepository;
use Botble\Demo\Repositories\Interfaces\CategoryInterface;
use Botble\Demo\Repositories\Interfaces\DemoInterface;
use Event;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;
use Language;

class DemoServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(DemoInterface::class, function () {
            return new DemoCacheDecorator(new DemoRepository(new Demo));
        });

        $this->app->bind(CategoryInterface::class, function () {
            return new CategoryCacheDecorator(new CategoryRepository(new Category()));
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
                Language::registerModule([Demo::class]);
            }

            dashboard_menu()->registerItem([
                'id' => 'cms-plugins-demo',
                'priority' => 5,
                'parent_id' => null,
                'name' => 'plugins/demo::demo.name',
                'icon' => 'fa fa-list',
                'url' => route('demo.index'),
                'permissions' => ['demo.index'],
            ])->registerItem([
                'id' => 'cms-plugins-demo-demo',
                'priority' => 1,
                'parent_id' => 'cms-plugins-demo',
                'name' => 'plugins/demo::demo.name',
                'icon' => null,
                'url' => route('demo.index'),
                'permissions' => ['demo.index'],
            ])->registerItem([
                'id' => 'cms-plugins-demo-category',
                'priority' => 2,
                'parent_id' => "cms-plugins-demo",
                'name' => 'plugins/demo::category.name',
                'icon' => null,
                'url' => route('category.index'),
                'permissions' => ['demo.index'],
            ]);
        });
    }
}
