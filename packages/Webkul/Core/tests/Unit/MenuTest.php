<?php

use Illuminate\Support\Collection;
use Webkul\Core\Menu;
use Webkul\Core\Menu\MenuItem;

/**
 * Create config for menu items.
 */
beforeEach(function () {
    config()->set('menu.admin', [
        [
            'key'        => 'dashboard',
            'name'       => 'admin::app.components.layouts.sidebar.dashboard',
            'route'      => 'admin.dashboard.index',
            'sort'       => 1,
            'icon'       => 'icon-dashboard',
        ], [
            'key'        => 'sales',
            'name'       => 'admin::app.components.layouts.sidebar.sales',
            'route'      => 'admin.sales.orders.index',
            'sort'       => 2,
            'icon'       => 'icon-sales',
        ], [
            'key'        => 'sales.orders',
            'name'       => 'admin::app.components.layouts.sidebar.orders',
            'route'      => 'admin.sales.orders.index',
            'sort'       => 1,
            'icon'       => '',
        ],
    ]);
});

it('should add and get menu items', function () {
    // Arrange.
    $menu = new Menu();

    foreach (config('menu.admin') as $menuItem) {
        $menu->add(new MenuItem(
            key: $menuItem['key'],
            name: trans($menuItem['name']),
            route: $menuItem['route'],
            sort: $menuItem['sort'],
            icon: $menuItem['icon'],
            menuItems: collect([]),
        ));
    }

    $menuItems = $menu->getItems();

    // Act and Assert.
    $this->loginAsAdmin();

    expect($menuItems->first()->key)->toBe('dashboard');

    expect($menuItems->first()->name)->toBe(trans('admin::app.components.layouts.sidebar.dashboard'));

    expect($menuItems->first()->route)->toBe('admin.dashboard.index');

    expect($menuItems->first()->sort)->toBe(1);

    expect($menuItems->first()->icon)->toBe('icon-dashboard');

    expect($menuItems->last()->key)->toBe('sales');
});

it('should prepare menu items', function () {
    // Arrange.
    $menu = new Menu();

    $class = new ReflectionClass(Menu::class);

    $method = $class->getMethod('prepareMenuItems');

    $method->invoke($menu);

    // Act and Assert.
    $this->loginAsAdmin();

    $menuItems = $menu->getItems();

    expect($menuItems)->toBeInstanceOf(Collection::class);

    expect($menuItems->count())->toBe(2);

    expect($menuItems->first())->toBeInstanceOf(MenuItem::class);

    expect($menuItems->first()->key)->toBe('dashboard');

    expect($menuItems->first()->name)->toBe(trans('admin::app.components.layouts.sidebar.dashboard'));

    expect($menuItems->first()->route)->toBe('admin.dashboard.index');

    expect($menuItems->first()->sort)->toBe(1);

    expect($menuItems->first()->icon)->toBe('icon-dashboard');

    expect($menuItems->last()->key)->toBe('sales');
});

it('should process sub menu items', function () {
    // Arrange.
    $menu = new Menu();

    $class = new ReflectionClass(Menu::class);

    $method = $class->getMethod('processSubMenuItems');

    $subMenuItems = $method->invoke($menu, config('menu.admin'));

    // Act and Assert.
    $this->loginAsAdmin();

    expect($subMenuItems)->toBeInstanceOf(Collection::class);

    expect($subMenuItems->count())->toBe(3);

    expect($subMenuItems->first())->toBeInstanceOf(MenuItem::class);

    expect($subMenuItems->first()->key)->toBe('dashboard');

    expect($subMenuItems->first()->name)->toBe(trans('admin::app.components.layouts.sidebar.dashboard'));

    expect($subMenuItems->first()->route)->toBe('admin.dashboard.index');

    expect($subMenuItems->first()->sort)->toBe(1);

    expect($subMenuItems->first()->icon)->toBe('icon-dashboard');

    expect($subMenuItems->last()->key)->toBe('sales');

    expect($subMenuItems->last()->name)->toBe(trans('admin::app.components.layouts.sidebar.sales'));
});

it('should get children of menu item', function () {
    // Arrange.
    $menu = new Menu();

    // Act and Assert.
    $this->loginAsAdmin();

    $menuItem = $menu->getItems();

    expect($menuItem->first()->getChildren())->toBeNull();

    expect($menuItem->last()->getChildren())->toBeInstanceOf(Collection::class);

    expect($menuItem->last()->getChildren()->count())->toBe(1);

    expect($menuItem->last()->getChildren()->first()->key)->toBe('sales.orders');

    expect($menuItem->last()->getChildren()->first()->name)->toBe(trans('admin::app.components.layouts.sidebar.orders'));

    expect($menuItem->last()->getChildren()->first()->route)->toBe('admin.sales.orders.index');

    expect($menuItem->last()->getChildren()->first()->sort)->toBe(1);

    expect($menuItem->last()->getChildren()->first()->icon)->toBe('');
});

it('should check that menu item have children or not', function () {
    // Arrange.
    $menu = new Menu();

    // Act and Assert.
    $this->loginAsAdmin();

    $menuItem = $menu->getItems();

    expect($menuItem->first()->haveItem())->toBeFalse();

    expect($menuItem->last()->haveItem())->toBeTrue();
});

it('should get the url of the menu item', function () {
    // Arrange.
    $menu = new Menu();

    // Act and Assert.
    $this->loginAsAdmin();

    $menuItem = $menu->getItems();

    expect($menuItem->first()->getUrl())->toBe(route('admin.dashboard.index'));

    expect($menuItem->last()->getChildren()->first()->getUrl())->toBe(route('admin.sales.orders.index'));
});

it('should get the icon of menu item', function () {
    // Arrange.
    $menu = new Menu();

    // Act and Assert.
    $this->loginAsAdmin();

    $menuItem = $menu->getItems();

    expect($menuItem->first()->getIcon())->toBe('icon-dashboard');

    expect($menuItem->last()->getChildren()->first()->getIcon())->toBe('');
});

it('should get the name of menu item', function () {
    // Arrange.
    $menu = new Menu();

    // Act and Assert.
    $this->loginAsAdmin();

    $menuItem = $menu->getItems();

    expect($menuItem->first()->getName())->toBe(trans('admin::app.components.layouts.sidebar.dashboard'));
});

it('should get the route of menu item', function () {
    // Arrange.
    $menu = new Menu();

    // Act and Assert.
    $this->loginAsAdmin();

    $menuItem = $menu->getItems();

    expect($menuItem->first()->getRoute())->toBe('admin.dashboard.index');
});
