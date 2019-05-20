<?php

namespace Tests\Unit\HouseTest;

use App\House;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Foundation\Testing\TestResponse as Response;


/** @group House */
class HouseCreateTest extends TestCase
{

  /**
   * Resets the entire migration after the tests
   */
  use RefreshDatabase;

  /**
   * [Preferred] Wraps queries in transactions and rolls back after the tests
   * @param array $connectionsToTransact  A list of connections for multiple databases
   */
//  use DatabaseTransactions;
  protected $connectionsToTransact = [];


  protected $model_name = House::class;
  protected $model;

  protected function model() {
    if(!$this->model) {
      $this->model = app($this->model_name);
    }
    return $this->model;
  }

  /**
   * Returns instance(s) of the 'model_name' class according to the $count argument
   * @param int   $count
   * @return mixed
   */
  protected function factory(int $count = 1)
  {
    return factory($this->model_name, $count);
  }

  /** @test */
  public function root_redirects_to_houses_index()
  {
    $response = $this->get('/');
    $response->assertStatus(302);
    $response->assertRedirect( route('house.index') );
  }

  /** @test */
  public function get_houses_index() {
    $response = $this->get( $this->model()->route->index );
    $response->assertSuccessful();
    $response->assertViewIs('house.index');
    $response->assertViewHasAll(['houses', 'categories', 'user', 'category']);
    $response->assertSee('Articles');
    return $response;
  }

  private function _assert_DB_has_houses(Collection $houses) {
    $houses->each(function ($house) use ($houses) {
      $this->assertDatabaseHas('houses', ['title' => $house->title]);
    });
  }

  private function _DB_store_houses($count) {
    $houses = $this->factory($count)->create();

    $this->assertCount($count, $houses);

    $this->_assert_DB_has_houses($houses);

    return $houses;
  }

  /** @test */
  public function house_index_fetches_houses() {
    $houses_count = 2;

    $created_houses = $this->_DB_store_houses($houses_count);
    $this->assertCount($houses_count, $created_houses);

    $response = $this->get_houses_index();

    $fetched_houses = $response->viewData('houses');
    $this->assertCount($houses_count, $fetched_houses);

    $latest_house = $fetched_houses->sortBy('published_at', 0, 'desc')->first();

    $response->assertJsonStructure([
      'id', 'title', 'body', 'slug', 'user_id', 'category_id', 'published_at'
    ], $latest_house);
  }

  /** @test */
  public function house_index_fetches_only_active_published_houses() {
    $first_house = $this->factory()
      ->state('deleted_category')
      ->create(['title' => 'First Article With Deleted Category'])
      ->first();

    $second_house = $this->factory()
      ->state('not_yet_published')
      ->create(['title' => 'Second Article Not Yet Published'])
      ->first();

    $third_house = $this->factory()
      ->state('deleted_house')
      ->create(['title' => 'Third Article Already Deleted'])
      ->first();

    $fourth_house = $this->factory()
      ->create(['title' => 'Fourth Active Article'])
      ->first();

    $invalid_houses = [
      $first_house, $second_house, $third_house
    ];


    $this->_assert_DB_has_houses(
      collect([$first_house, $second_house, $third_house, $fourth_house])
    );

    $response = $this->get_houses_index();

    $fetched_titles = $response->viewData('houses')->pluck('title', 'id');


    foreach($invalid_houses as $invalid_house) {
      $this->assertArrayNotHasKey( $invalid_house->id, $fetched_titles);
    }
  }

}
