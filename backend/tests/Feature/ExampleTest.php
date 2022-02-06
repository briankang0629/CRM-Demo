<?php

namespace Tests\Feature;


use Tests\UserTestCase;

class ExampleTest extends UserTestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/api/product');

        $response->assertStatus(200);
    }
}
