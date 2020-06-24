<?php

return [

    'GET <controller:\w+/?>' => '<controller>/list',
    'POST <controller:\w+/?>' => '<controller>/create',
    'GET <controller:\w+>/<id:\d+>' => '<controller>/get',
    'PATCH <controller:\w+>/<id:\d+>' => '<controller>/patch',
    'DELETE <controller:\w+>/<id:\d+>' => '<controller>/delete',

    'POST user-session/login' => 'user-session/login',
    'POST user-session/logout' => 'user-session/logout',
    'POST user-session/register' => 'user-session/register',

];