<?php

namespace {$namespace}\Controllers;

class DebugController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return addon_view(addon_name(), 'index');
    }
}
