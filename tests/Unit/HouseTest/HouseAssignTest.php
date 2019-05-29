<?php

namespace Tests\Unit\HouseTest;

use App\Role;
use App\User;
use App\House;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Foundation\Testing\TestResponse as Response;


/** @group House */
class HouseAssignTest extends TestCase
{

  /**
   * Resets the entire migration after the tests
   */
//  use RefreshDatabase;

  /**
   * [Preferred] Wraps queries in transactions and rolls back after the tests
   * @param array $connectionsToTransact  A list of connections for multiple databases
   */
  use DatabaseTransactions;

  protected $connectionsToTransact = [];

  protected $model = House::class;


  protected function model() {
    return app($this->model);
  }


  protected function clearAllPivots() {
    // ToDo: refactor this
    DB::statement('update house_user' . ' set expires_at = null where 1');
  }


  /** @test */
  public function root_redirects_to_houses_index()
  {
    $house_index_route = $this->model()->route->index;

    $response = $this->get('/');
    $response->assertStatus(302);
    $response->assertRedirect( $house_index_route );
  }


  /** @test */
  public function user_can_apply_for_house()
  {
    $this->clearAllPivots();

    $user = User::where('id', '!=', 1)->get()->random();
    $house = House::query()->available()->get()->random();

    $headers = [
      'Content-Type' => 'application/json',
      'Accept' => 'application/json',
    ];

    $response = $this
      ->actingAs($user, 'api')
      ->postJson("/api/available-houses/{$house->slug}/apply", [], $headers);

    $response->assertStatus(200);
  }


  /** @test */
  public function admin_can_approve_user_house_application()
  {
    $house = House::query()->applied()->get()->random();
    $user = $house->applicants->first();

    $approve_route = $house->route->assign($user);

    $valid_data = [
      'action' => 'approve',
      'expires_at' => Carbon::now()->addMonth(16)->toDateString()
    ];

    $headers = [
      'Content-Type' => 'application/json',
      'Accept' => 'application/json'
    ];

    $admin = Role::instance()->getAdmin();

    $response = $this
      ->actingAs($admin, 'web')
      ->json('PUT', $approve_route, $valid_data, $headers);

    $response->assertStatus(200);
    $response->assertJsonStructure(['message']);
    $response->assertSeeText(House::SUCCESS_APPROVED );
  }


  /** @test */
  public function admin_can_decline_user_house_application()
  {
    $house = House::query()->applied()->get()->random();
    $user = $house->applicants->first();

    $decline_route = $house->route->assign($user);

    $valid_data = [
      'action' => 'decline',
    ];

    $headers = [
      'Content-Type' => 'application/json',
      'Accept' => 'application/json'
    ];

    $admin = Role::instance()->getAdmin();

    $response = $this
      ->actingAs($admin, 'web')
      ->json('PUT', $decline_route, $valid_data, $headers);

    $response->assertStatus(200);
    $response->assertJsonStructure(['message']);
    $response->assertSeeText(House::SUCCESS_DECLINED);
  }


  /** @test */
  public function house_approval_input_must_contain_valid_input()
  {
    $house = House::query()->applied()->get()->random();
    $user = $house->applicants->first();

    $decline_route = $house->route->assign($user);

    $valid_data = [
      'action' => 'approve',
      'expires_at' => Carbon::now()->addMonth(16)->toDateString()
    ];

    $action_is_not_approve_or_decline = [
      'action' => 'walk',
      'expires_at' => Carbon::now()->addMonth(16)->toDateString()
    ];

    $expires_at_is_empty = [
      'action' => 'approve',
      'expires_at' => ''
    ];

    $expires_at_is_past = [
      'action' => 'approve',
      'expires_at' => Carbon::now()->subSecond()->toDateString()
    ];

    $headers = [
      'Content-Type' => 'application/json',
      'Accept' => 'application/json'
    ];

    $admin = Role::instance()->getAdmin();

    $response_valid = $this
      ->actingAs($admin, 'web')
      ->json('PUT', $decline_route, $valid_data, $headers);

    $response_valid->assertStatus(200);
    $response_valid->assertJsonStructure(['message']);
    $response_valid->assertSeeText(House::SUCCESS_APPROVED);

    $response_invalid_action = $this
      ->actingAs($admin, 'web')
      ->json('PUT', $decline_route, $action_is_not_approve_or_decline, $headers);

    $response_invalid_action->assertStatus(422);
    $response_invalid_action->assertJsonStructure(['errors']);
    $response_invalid_action->assertSeeText('action');

    $response_empty_expiry = $this
      ->actingAs($admin, 'web')
      ->json('PUT', $decline_route, $expires_at_is_empty, $headers);

    $response_empty_expiry->assertStatus(422);
    $response_empty_expiry->assertJsonStructure(['errors']);
    $response_empty_expiry->assertSeeText('expires_at');

    $response_past_expiry = $this
      ->actingAs($admin, 'web')
      ->json('PUT', $decline_route, $expires_at_is_past, $headers);

    $response_past_expiry->assertStatus(422);
    $response_past_expiry->assertJsonStructure(['errors']);
    $response_past_expiry->assertSeeText('expires_at');
  }


  /** @test */
  public function rented_house_cannot_be_approved()
  {
    $house = factory($this->model)->create();

    // An $applicant applies for house
    $applicant = User::where('id', '!=', 1)->get()->random();
    $house->applicants()->attach($applicant);

    // A different $tenant rents house
    $tenant = User::whereNotIn('id', [1, $applicant->id])->get()->random();
    $house->tenants()->attach(
      $tenant, ['expires_at' => Carbon::now()->addMonth(12)->toDateString()]
    );

    // Admin attempts to approve a rented house (Approval Button will be disabled in UI anyway)
    $approve_route = $house->route->assign($applicant);

    $valid_data = [
      'action' => 'approve',
      'expires_at' => Carbon::now()->addMonth(16)->toDateString()
    ];

    $headers = [
      'Content-Type' => 'application/json',
      'Accept' => 'application/json'
    ];

    $admin = Role::instance()->getAdmin();

    $response = $this
      ->actingAs($admin, 'web')
      ->json('PUT', $approve_route, $valid_data, $headers);

    // Should fail
    $response->assertStatus(400);
    $response->assertJsonStructure(['message']);
    $response->assertSeeText( $house->errorRented() );
  }

}
