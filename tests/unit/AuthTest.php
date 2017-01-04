<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{

  /** @test */
  public function user_with_invalid_crendetials_cannot_login()
  {
//    $this->visit('login')
//        ->type(9899489788, 'phone')
//        ->type('tekken', 'password')
//        ->press('login')
//        ->see('Invalid Credentials !');
  }

  /** @test */
  public function login_with_invalid_data()
  {
//    $response = $this->post('/login', [
//      'phone'     => '12312',
//      'password'  => ''
//    ]);
//
//    $this->assertRedirectedTo(route('users.login.get'));
  }

  /** @test */
  public function login_with_valid_data()
  {
//    $response = $this->post('login', [
//      '_token'    => csrf_token(),
//      'phone'     => 9899489788,
//      'password'  => 'tekkenLORD!@34'
//    ]);
//
//    $this->assertRedirectedTo(route('pages.index'));
  }

  /** @test */
  public function user_with_correct_credentials_can_login()
  {
//    $this->visit('login')
//        ->type(9899489788, 'phone')
//        ->type('tekkenLORD!@34', 'password')
//        ->press('login')
//        ->see('Yo Yo');
  }

  /** @test */
  public function user_cannot_be_registered_without_passing_validations()
  {
//    $this->visit('register')
//        ->press('register')
//        ->see('The email field is required.');
  }

}
