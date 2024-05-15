<?php

use Illuminate\Support\Collection;
use Webkul\Core\Menu;
use Webkul\Core\Menu\MenuItem;

it('should add and get menu items', function () {
    $menu = new Menu();

    $menu->add(new MenuItem(
        key: 'key1',
        name: 'Dashboard',
        route: 'admin.dashboard.index',
        sort: 1,
        icon: 'icon-dashboard',
        menuItems: collect([]),
    ));

    $menu->add(new MenuItem(
        key: 'key2',
        name: 'Sales',
        route: 'admin.sales.index',
        sort: 2,
        icon: 'icon-sales',
        menuItems: collect([]),
    ));

    expect($menu->getItems()->count())->toBe(2);

    expect($menu->getItems()->first()->key)->toBe('key1');

    expect($menu->getItems())->toBeInstanceOf(Collection::class);

    expect($menu->getItems()->first())->toBeInstanceOf(MenuItem::class);
});

it('should prepare menu items', function () {
    $menu = new Menu();

    $menu->prepareMenuItems();

    expect($menu->getItems())->toBeInstanceOf(Collection::class);
});

it('should process sub menu items', function () {
    $menu = new Menu();

    $subMenuItems = $menu->processSubMenuItems([
        'sales' => [
            'key'    => 'sales',
            'name'   => 'admin::app.components.layouts.sidebar.sales',
            'route'  => 'admin.sales.orders.index',
            'sort'   => 2,
            'icon'   => 'icon-sales',
            'orders' => [
                'key'   => 'sales.orders',
                'name'  => 'admin::app.components.layouts.sidebar.orders',
                'route' => 'admin.sales.orders.index',
                'sort'  => 1,
                'icon'  => '',
            ],
            'shipments' => [
                'key'   => 'sales.shipments',
                'name'  => 'admin::app.components.layouts.sidebar.shipments',
                'route' => 'admin.sales.shipments.index',
                'sort'  => 2,
                'icon'  => '',
            ],
        ],
    ]);

    expect($subMenuItems->count())->toBe(1);

    expect($subMenuItems->first())->toBeInstanceOf(MenuItem::class);

    expect($subMenuItems->first()->menuItems)->toBeInstanceOf(Collection::class);

    expect($subMenuItems->first()->menuItems->first())->toBeInstanceOf(MenuItem::class);

    expect($subMenuItems->first()->key)->toBe('sales');

    expect($subMenuItems->first()->icon)->toBe('icon-sales');

    expect($subMenuItems->first()->sort)->toBe(2);

    expect($subMenuItems->first()->menuItems->count())->toBe(2);

    expect($subMenuItems->first()->menuItems->first()->key)->toBe('orders');

    expect($subMenuItems->first()->menuItems->last()->key)->toBe('shipments');

    expect($subMenuItems->first()->menuItems->first()->route)->toBe('admin.sales.orders.index');

    expect($subMenuItems->first()->menuItems->first()->icon)->toBe('');

    expect($subMenuItems->first()->menuItems->last()->icon)->toBe('');
});

it('should prepare menuitem', function () {
    $menuItem = new MenuItem(
        key: 'key1',
        name: 'Dashboard',
        route: 'admin.dashboard.index',
        sort: 1,
        icon: 'icon-dashboard',
        menuItems: collect([]),
    );

    expect($menuItem->getName())->toBe('Dashboard');

    expect($menuItem->getIcon())->toBe('icon-dashboard');

    expect($menuItem->getRoute())->toBe('admin.dashboard.index');

    expect($menuItem->getUrl())->toBe(route('admin.dashboard.index'));

    expect($menuItem->haveItem())->toBe(false);
});

it('should prepare sub menu items', function () {
    $menuItem = new MenuItem(
        key: 'key1',
        name: 'Dashboard',
        route: 'admin.dashboard.index',
        sort: 1,
        icon: 'icon-dashboard',
        menuItems: collect([
            new MenuItem(
                key: 'key2',
                name: 'Sales',
                route: 'admin.sales.index',
                sort: 2,
                icon: 'icon-sales',
                menuItems: collect([]),
            ),
        ]),
    );

    expect($menuItem->haveItem())->toBe(true);

    expect($menuItem->menuItems->count())->toBe(1);

    expect($menuItem->menuItems->first())->toBeInstanceOf(MenuItem::class);

    expect($menuItem->menuItems->first()->key)->toBe('key2');

    expect($menuItem->menuItems->first()->name)->toBe('Sales');

    expect($menuItem->menuItems->first()->route)->toBe('admin.sales.index');

    expect($menuItem->menuItems->first()->sort)->toBe(2);
});

it('should prepare menu item with null menu items', function () {
    $menuItem = new MenuItem(
        key: 'key1',
        name: 'Dashboard',
        route: 'admin.dashboard.index',
        sort: 1,
        icon: 'icon-dashboard',
        menuItems: null,
    );

    expect($menuItem->haveItem())->toBe(false);
});

it('should get children of menu item', function () {
    $menuItem = new MenuItem(
        key: 'key1',
        name: 'Dashboard',
        route: 'admin.dashboard.index',
        sort: 1,
        icon: 'icon-dashboard',
        menuItems: collect([
            new MenuItem(
                key: 'key2',
                name: 'Sales',
                route: 'admin.sales.index',
                sort: 2,
                icon: 'icon-sales',
                menuItems: collect([]),
            ),
        ]),
    );

    expect($menuItem->getChildren())->toBeInstanceOf(Collection::class);

    expect($menuItem->getChildren()->count())->toBe(1);

    expect($menuItem->getChildren()->first())->toBeInstanceOf(MenuItem::class);
});

it('should get null children of menu item', function () {
    $menuItem = new MenuItem(
        key: 'key1',
        name: 'Dashboard',
        route: 'admin.dashboard.index',
        sort: 1,
        icon: 'icon-dashboard',
        menuItems: null,
    );

    expect($menuItem->getChildren())->toBeNull();
});
