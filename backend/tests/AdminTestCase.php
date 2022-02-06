<?php

namespace Tests;


class AdminTestCase extends TestCase
{
    use CreatesApplication;

    protected $baseUrl = 'http://admin.localhost';

    public function __construct()
    {
        parent::__construct();
    }
}
