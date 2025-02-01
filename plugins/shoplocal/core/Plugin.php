<?php

namespace ShopLocal\Core;

use Backend;
use Backend\Models\UserRole;
use ShopLocal\Core\Components\ListRetailers;
use System\Classes\PluginBase;

/**
 * Core Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     */
    public function pluginDetails(): array
    {
        return [
            'name'        => 'shoplocal.core::lang.plugin.name',
            'description' => 'shoplocal.core::lang.plugin.description',
            'author'      => 'ShopLocal',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     */
    public function register(): void
    {

    }

    /**
     * Boot method, called right before the request route.
     */
    public function boot(): void
    {

    }

    /**
     * Registers any frontend components implemented in this plugin.
     */
    public function registerComponents(): array
    {
        return [
            ListRetailers::class => 'listRetailers',
        ];
    }

    /**
     * Registers any backend permissions used by this plugin.
     */
    public function registerPermissions(): array
    {
        return [
            'shoplocal.core.retailers.manage_all' => [
                'tab' => 'shoplocal.core::lang.plugin.name',
                'label' => 'shoplocal.core::lang.permissions.manage_retailers',
                'roles' => [UserRole::CODE_DEVELOPER, UserRole::CODE_PUBLISHER],
            ],
        ];
    }

    /**
     * Registers backend navigation items for this plugin.
     */
    public function registerNavigation(): array
    {
        return [
            'core' => [
                'label'       => 'shoplocal.core::lang.models.retailer.label_plural',
                'url'         => Backend::url('shoplocal/core/retailers'),
                'icon'        => 'icon-cart-shopping',
                'permissions' => ['shoplocal.core.*'],
                'order'       => 500,
                'sideMenu' => [
                    'retailers' => [
                        'label'       => 'shoplocal.core::lang.models.retailer.label_plural',
                        'url'         => Backend::url('shoplocal/core/retailers'),
                        'icon'        => 'icon-cart-shopping',
                        'permissions' => ['shoplocal.core.*'],
                        'order'       => 500,
                    ],
                    'retailercategories' => [
                        'label'       => 'shoplocal.core::lang.models.retailercategory.label_plural',
                        'url'         => Backend::url('shoplocal/core/retailercategories'),
                        'icon'        => 'icon-boxes',
                        'permissions' => ['shoplocal.core.*'],
                        'order'       => 500,
                    ],
                ],
            ],
        ];
    }
}
