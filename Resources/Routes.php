<?php

use iButenko\App\Route;

return [
    new Route('login', 'Login', 'index'),

    new Route('logout', 'Logout', 'index'),

    new Route('/', 'Task', 'page'),

    new Route('tasks/%', 'Task', 'page'),

    new Route('task/create', 'Task', 'create'),

    new Route('task/edit/%', 'Task', 'edit'),
    new Route('task/edit', 'Task', 'edit'),

    new Route('task/delete/%', 'Task', 'delete'),

    new Route('task/done/%', 'Task', 'done'),
];
