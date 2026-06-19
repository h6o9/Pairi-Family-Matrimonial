<?php

namespace App\Enums;

enum RouteList
{
    public static function getAll(): object
    {
        $route_list = [
            (object) [
                'name' => __('Dashboard'),
                'route' => route('admin.dashboard'),
                'permission' => null,
            ],
            (object) [
                'name' => __('Users'),
                'route' => route('admin.users.index'),
                'permission' => null,
            ],
        ];

        return (object) $route_list;
    }
}
