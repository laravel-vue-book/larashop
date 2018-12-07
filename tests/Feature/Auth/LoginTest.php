<?php

namespace Tests\Feature\Auth;

use Auth;
use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Auth\Notifications\ResetPassword;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_user_can_view_a_login_form()
    {   
        $response = $this->get('/login');
        $response->assertSuccessful();
        $response->assertViewIs('auth.login');
    }

    public function test_user_cannot_view_a_login_form_when_authenticated()
    {
        $user = factory(User::class)->make();
        $response = $this->actingAs($user)->get('/login');
        $response->assertRedirect('/home');
    }

    public function test_user_can_login_with_correct_credentials()
    {
        $password = "dedihartono";

        $user = factory(User::class)->create([
            'name'     => 'Dedi Hartono',
            'email'    => 'dedihartono1993@gmail.com',
            'username' => 'dedihartono',
            'password' => \Hash::make($password),
            'roles'    => json_encode(["ADMIN"]),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);
        $response->assertRedirect('/home');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_incorrect_password()
    {
        $user = factory(User::class)->create([
            'username' => 'dedihartono',
            'password' => \Hash::make("dedihartono"),
            'roles'    => json_encode(["ADMIN"]),
        ]);
        
        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'invalid-password',
        ]);
        
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function test_remember_me_functionality()
    {
        $password = "dedihartono";
        $user = factory(User::class)->create([
            'id' => random_int(1, 100),
            'username' => 'dedihartono',
            'password' => \Hash::make($password),
            'roles'    => json_encode(["ADMIN"]),
        ]);
        
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
            'remember' => 'on',
        ]);
        
        $response->assertRedirect('/home');
        $response->assertCookie(Auth::guard()->getRecallerName(), vsprintf('%s|%s|%s', [
            $user->id,
            $user->getRememberToken(),
            $user->password,
        ]));
        $this->assertAuthenticatedAs($user);
    }

}
