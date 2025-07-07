<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_returns_success()
    {


        $response = $this->get('/list-product');

        // Assert
        $response->assertStatus(200);
        $response->assertSee("Mango Juice");
    }
}
