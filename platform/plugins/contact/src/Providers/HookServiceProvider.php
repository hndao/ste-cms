<?php

namespace Botble\Contact\Providers;

use Html;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Botble\Contact\Repositories\Interfaces\ContactInterface;

class HookServiceProvider extends ServiceProvider
{
    /**
     * @throws \Throwable
     */
    public function boot()
    {
        add_filter(BASE_FILTER_TOP_HEADER_LAYOUT, [$this, 'registerTopHeaderNotification'], 120);
        add_filter(BASE_FILTER_APPEND_MENU_NAME, [$this, 'getUnReadCount'], 120, 2);

        if (function_exists('add_shortcode')) {
            add_shortcode('contact-form', __('Contact form'), __('Add contact form'), [$this, 'form']);
            shortcode()
                ->setAdminConfig('contact-form', view('plugins/contact::partials.short-code-admin-config')->render());
        }
    }

    /**
     * @param string $options
     * @return string
     *
     * @throws \Throwable
     */
    public function registerTopHeaderNotification($options)
    {
        if (Auth::user()->hasPermission('contacts.edit')) {
            $contacts = $this->app->make(ContactInterface::class)
                ->getUnread(['id', 'name', 'email', 'phone', 'created_at']);

            if ($contacts->count() == 0) {
                return null;
            }

            return $options . view('plugins/contact::partials.notification', compact('contacts'))->render();
        }
        return null;
    }

    /**
     * @param int $number
     * @param string $menuId
     * @return string
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getUnReadCount($number, $menuId)
    {
        if ($menuId == 'cms-plugins-contact') {
            $unread = $this->app->make(ContactInterface::class)->countUnread();
            if ($unread > 0) {
                return Html::tag('span', (string)$unread, ['class' => 'badge badge-success'])->toHtml();
            }
        }

        return $number;
    }

    /**
     * @return string
     * @throws \Throwable
     */
    public function form($shortcode)
    {
        $view = 'plugins/contact::forms.contact';

        if ($shortcode->view && view()->exists($shortcode->view)) {
            $view = $shortcode->view;
        }
        return view($view, ['header' => $shortcode->header])->render();
    }
}
