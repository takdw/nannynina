<?php

namespace Tests\Unit;

use App\Models\FightClub;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function belongs_to_a_fight_club()
    {
        $club = FightClub::factory()->create();
        $fighter = User::factory()->create([
            'fight_club_id' => $club->id,
        ]);

        $this->assertTrue($fighter->club->is($club));
    }
}
