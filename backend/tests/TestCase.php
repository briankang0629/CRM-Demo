<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $baseUrl = 'localhost';

    public function __construct()
    {
        parent::__construct();
        ini_set('memory_limit', '1280M');
    }
}
