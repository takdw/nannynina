<?php

namespace Tests\Unit;

use App\Models\FightClub;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FightClubTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_have_fighters()
    {
        $club = FightClub::factory()->create();
        $fighters = User::factory()->count(3)->create(['fight_club_id' => $club->id]);

        $this->assertCount(3, $club->fighters);
    }
}
