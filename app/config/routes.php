<?php

return [
    //StartController
    '' => [
        'controller' => 'start',
        'action' => 'index',
    ],

    //AccountController
    'account/login' => [
        'controller' => 'account',
        'action' => 'login',
    ],

    'account/logout' => [
        'controller' => 'account',
        'action' => 'logout',
    ],

    'account/delete' => [
        'controller' => 'account',
        'action' => 'delete',
    ],

    'account/signup' => [
        'controller' => 'account',
        'action' => 'signup',
    ],

    'account/recovery' => [
        'controller' => 'account',
        'action' => 'recovery',
    ],

    'account/new_pass' => [
        'controller' => 'account',
        'action' => 'new_pass',
    ],

    //MainController
    'main' => [
        'controller' => 'main',
        'action' => 'main',
    ],
    'main/note' => [
        'controller' => 'main',
        'action' => 'note',
    ],

    //SettingsController

    'settings' => [
        'controller' => 'settings',
        'action' => 'settings',
    ],

    'settings/change_login' => [
        'controller' => 'settings',
        'action' => 'change_login',
    ],

    'settings/change_pass' => [
        'controller' => 'settings',
        'action' => 'change_pass',
    ],

    'settings/total_amount' => [
        'controller' => 'settings',
        'action' => 'total_amount',
    ],
];
