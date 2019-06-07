<?php
namespace Tests\Feature;
use App\Entities\Album;
use App\Entities\Song;
use App\Entities\User;
use Tests\ControllerTestCase;


class GenreTest extends ControllerTestCase
{
    
    private $endpoint = 'api/v1/genres';
    
    public function setUp(): void
    {
        parent::setUp();
        $album = factory(Album::class)->create();
        $this->album = $album;
    }
    /** @test */
    function user_can_create_a_genre()
    {

        $artiste = factory(User::class)->create();
        $input = [
            'name' => 'The greatest',
            'description' => 'genre'
        ];
        $this->actingAs($artiste, 'api');
        // Act
        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
        ])->json('POST', $this->endpoint, $input);
        // Assert
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => $input['name']
        ]);
    }
    /** @test */
    function user_can_view_all_albums()
    {
       
        // Act
        $response = $this->get($this->endpoint);
        // Assert
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => $this->album->name,
            'description' => $this->album->description
        ]);
    }
}