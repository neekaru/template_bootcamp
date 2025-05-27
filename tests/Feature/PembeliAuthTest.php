<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pembeli;
use Illuminate\Support\Facades\Hash;

class PembeliAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_pembeli_can_register()
    {
        $response = $this->post(route('register.submit'), [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('pembelis', [
            'username' => 'testuser',
            'email' => 'test@example.com',
        ]);
    }

    public function test_pembeli_can_login()
    {
        $pembeli = Pembeli::create([
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('login.submit'), [
            'username' => 'testuser',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($pembeli, 'pembeli');
    }

    public function test_pembeli_can_logout()
    {
        $pembeli = Pembeli::create([
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->actingAs($pembeli, 'pembeli');
        $response = $this->post(route('logout'));

        $response->assertRedirect('/');
        $this->assertGuest('pembeli');
    }
} 