<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Foundation\Testing\Concerns\InteractsWithAuthentication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

use App\User;

/** @group House */
class ExampleTest extends TestCase
{
    use InteractsWithAuthentication;


    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');
        $response->assertRedirect( route('house.index') );
    }

    public function testLoginTest()
    {
        // GET /login : View - log in
        $response = $this->get('/login');
        $response->assertViewIs('auth.login');
        $response->assertSeeInOrder(['E-Mail Address', 'Password', 'Remember Me']);

        // ToDo: refactor this
        // HOUSE /login : authenticate user
        $credentials = [
            'email' => 'mario44@elite.com', 'password' => 'mario44',
            '_token' => 'lIXB7CSBLlfZ7JIKgNd9p8NVRp0qEIJrkFaHh93d'
        ];
        $response = $this->post('/login', $credentials);
        $response->assertStatus(302);
        $this->assertAuthenticated();
    }

    public function testHousesTest()
    {
        // GET /houses : View - all houses
        $response = $this->get('/houses');
        $response->assertSuccessful();
        $response->assertViewIs('house.index');
        $response->assertViewHasAll(['houses']);
    }

    public function testShowHouseTest()
    {
        // GET /houses/:id : View - one house
        $response = $this->get('/houses/{wrongId}');
        $response->assertNotFound();

        $response = $this->get('/houses/2');
        $response->assertSuccessful();
        $response->assertViewIs('house.show');
        $response->assertViewHasAll(['house']);

        $this->assertEquals( $response->viewData('house')->id, 2 );
    }

    public function testCreateHouseTest()
    {
        // GET /houses/create : View - create house
        $response = $this->get('/houses/create');
        $this->assertGuest();
        $response->assertRedirect(route('login') );

        $user = Auth::loginUsingId(1);
        $this->assertAuthenticated();

        $response = $this->actingAs($user)->get('/houses/create');
        $response->assertViewIs('house.create');
        $response->assertViewHasAll(['categories']);
        $response->assertSeeInOrder(['Title', 'Body']);
    }

    public function testStoreHouseTest()
    {
        // HOUSE /houses : save new house
        $house = [
            'title' => 'Feature Testing',
            'body' => 'A sublime article on PHPUnit capabilities'
        ];

        $user = Auth::loginUsingId(1);
        $this->assertAuthenticated();

        $response = $this->actingAs($user)->post('/houses', $house);
        $response->assertSessionHas('message');
        $response->assertRedirect( route('house.index') );
    }

    public function testEditHouseTest()
    {
        // GET /houses/{house}/edit : View - edit house
        $response = $this->get('/houses/2/edit');
        $this->assertGuest();
        $response->assertRedirect( route('login') );

        $user = Auth::loginUsingId(1);
        $this->assertAuthenticated();

        $response = $this->actingAs($user)->get('/houses/2/edit');
        $response->assertViewIs('house.edit');
        $response->assertViewHasAll(['categories', 'house']);
        $response->assertSeeInOrder(['Title', 'Body']);
    }

    public function testUpdateHouseTest()
    {
        // PUT /houses/{house} : update house
        $patch = [
            'title' => 'New Feature Testing',
            'body' => 'A sublime article on PHPUnit capabilities, updated Jan. 2019'
        ];

        $user = Auth::loginUsingId(1);
        $this->assertAuthenticated();

        $response = $this->actingAs($user)->put('/houses/2', $patch);
        $response->assertRedirect( route('house.show', ['house' => 2]) );
    }

    public function testDeleteHouseTest()
    {
        // DELETE /houses/{house} : update house
        $user = Auth::loginUsingId(1);
        $this->assertAuthenticated();

        $response = $this->actingAs($user)->delete('/houses/2');
        $response->assertSessionHas('message');
        $response->assertRedirect( route('house.index') );
    }



}
