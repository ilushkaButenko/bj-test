<?php

namespace iButenko\Controllers;

use iButenko\App\Controller;
use iButenko\App\View;

/**
 * NotFoundController
 */
class NotFoundController extends Controller
{
    public function index()
    {
        header('HTTP/1.0 404', true, 404);

        View::render('notfound');
    }
}
