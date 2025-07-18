<?php

use App\Helper\JWTToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_product()
    {
        // Arrange
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $mockUser = [
            'userID' =>$user->id,
            'userEmail' => $user->email
        ];


    $this->mock(JWTToken::class, function ($mock) use ($mockUser) {
        $mock->shouldReceive('VerifyToken')->andReturn($mockUser);
    });

        $formData = [
            'name' => "Mango Juice",
            'price' => 3.44,
            'unit' => 1000,
        ];

        // Act
      $response = $this->withCookie('token', 'fake-valid-token')->post('/create-product', $formData);

    // Assert
    $response->assertRedirect(); // or ->assertRedirect('/list-product') if you redirect there
    $this->assertDatabaseHas('products', [
        'name' => 'Mango Juice',
        'price' => 3.44,
    ]);
    }
}
