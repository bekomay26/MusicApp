<?php

namespace Tests\Feature;


use App\Entities\Song;
use App\Entities\User;

use Tests\ControllerTestCase;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SongTest extends ControllerTestCase
{

    
    // private $endpoint = 'api/v1/songs';
    
    // public function setUp(): void
    // {
    //     parent::setUp();
    //     $artiste = factory(User::class)->create([]);
    //     $song = factory(Song::class)->create();
    //     $this->artiste = $artiste;
    //     $this->song = $song;
    // }
    // /** @test */
    // function user_can_view_all_songs()
    // {
    //     // Act
    //     $response = $this->get($this->endpoint);
    //     // Assert
    //     $response->assertStatus(200);
    //     $response->assertJsonFragment([
    //         'name' => $this->song->name,
    //         'song_path' => $this->song->song_path,
    //         'description' => $this->song->description
    //     ]);
    // }
    // /** @test */
    // function user_can_view_a_single_song()
    // {
    //     // Act
    //     $response = $this->get("{$this->endpoint}/{$this->song->id}");
    //     // Assert
    //     $response->assertStatus(200);
    //     $response->assertJsonFragment([
    //         'name' => $this->song->name,
    //         'song_path' => $this->song->song_path,
    //         'description' => $this->song->description
    //     ]);
    // }
}
