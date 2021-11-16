<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilterUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_filter_users_based_on_a_single_parameter()
    {
        User::factory()->count(2)->create(['height' => 170]);
        User::factory()->count(3)->create(['height' => 189]);

        $response = $this->get('/users?filters=height:189');

        $response->assertOk()
            ->assertViewIs('users.index')
            ->assertViewHas('users');

        $users = $response->original->gatherData()['users'];
        $this->assertCount(3, $users);
        $users->each(function ($user) {
            $this->assertEquals($user->height, 189);
        });
    }

    /** @test */
    public function can_filter_users_based_on_a_two_parameters()
    {
        User::factory()->count(4)->create(['height' => 170, 'eye_color' => 'dark-gray']);
        User::factory()->count(3)->create(['height' => 189, 'eye_color' => 'blue']);
        User::factory()->count(2)->create(['height' => 170, 'eye_color' => 'blue']);

        $response = $this->get('/users?filters=height:170,eye_color:blue');

        $response->assertOk()
            ->assertViewIs('users.index')
            ->assertViewHas('users');

        $users = $response->original->gatherData()['users'];
        $this->assertCount(2, $users);
        $users->each(function ($user) {
            $this->assertEquals($user->height, 170);
            $this->assertEquals($user->eye_color, 'blue');
        });
    }

    /** @test */
    public function cannot_filter_with_filters_not_in_the_allowed_list()
    {
        User::factory()->count(4)->create(['stance' => 'south-paw']);
        User::factory()->count(4)->create(['stance' => 'orthodox']);

        $response = $this->get('/users?filters=stance:south-paw');

        $response->assertOk()
            ->assertViewIs('users.index')
            ->assertViewHas('users');

        $this->assertCount(8, $response->original->gatherData()['users']);
    }

    /** @test */
    public function filters_are_optional()
    {
        User::factory()->count(4)->create(['height' => 170, 'eye_color' => 'dark-gray']);
        User::factory()->count(3)->create(['height' => 189, 'eye_color' => 'blue']);
        User::factory()->count(2)->create(['height' => 170, 'eye_color' => 'blue']);

        $response = $this->get('/users');

        $response->assertOk()
            ->assertViewIs('users.index')
            ->assertViewHas('users');

        $this->assertCount(9, $response->original->gatherData()['users']);

        $responseB = $this->get('/users?filters=');

        $responseB->assertOk()
            ->assertViewIs('users.index')
            ->assertViewHas('users');

        $this->assertCount(9, $responseB->original->gatherData()['users']);
    }
}
