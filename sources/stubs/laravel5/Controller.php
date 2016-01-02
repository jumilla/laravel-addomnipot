<?php

namespace {$namespace}\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
	use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;
    use \Illuminate\Foundation\Bus\DispatchesJobs;
    use \Illuminate\Foundation\Validation\ValidatesRequests;

    public function __construct()
    {
        View::share('__addon', addon());
    }
}
