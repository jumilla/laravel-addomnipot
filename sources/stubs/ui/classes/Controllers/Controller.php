<?php

namespace {$namespace}\Controllers;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function __construct()
    {
        View::share('__addon', addon());
    }
}
