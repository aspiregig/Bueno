<?php namespace Bueno\Authenticate;

use Carbon\Carbon;
use Hash;
use Flash;
use Socialite;
use Bueno\Services\SmsAPI;
use Illuminate\Contracts\Auth\Guard;
use Bueno\Repositories\DbUserRepository;
use Bueno\Exceptions\ModelNotFoundException;
use Bueno\Exceptions\InvalidConfirmationCodeException;

class  AuthenticateUser {

    /**
     * @var DbUserRepository
     */
    private $userRepo;
    /**
     * @var Socialite
     */
    private $socialite;
    /**
     * @var Guard
     */
    private $auth;

    protected $sms;


    /**
     * constructor
     *
     * @param DbUserRepository $userRepo
     * @param Guard $auth
     */
    function __construct(DbUserRepository $userRepo, Guard $auth, SmsAPI $sms)
    {
      $this->sms = $sms;

      $this->auth = $auth;

      $this->userRepo = $userRepo;
    }

    /**
     * authenticates the user into the app. if user is not registered,
     * redirect it to the register page
     *
     * @param $driver
     * @param $hasCode
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function execute($driver, $hasCode, $inputs)
    {
        if($this->checkIfUserDeniedPermission($inputs))
        {
          Flash::danger("Either you denied to grant the permissions or some technical error occured!");

          return redirect()->route('users.login.get');
        }

        if( !$hasCode) return $this->getAuthorizationFirst($driver);

        $userData = $this->getSocialUser($driver);

        $userData = [
            'email'       => $userData->email,
            'name'        => $userData->name,
            'avatar'      => $userData->avatar,
            'first_name'  => isset($userData->user['first_name']) ? $userData->user['first_name'] : $userData->user['name']['givenName'],
            'last_name'   => isset($userData->user['last_name']) ? $userData->user['last_name'] : $userData->user['name']['familyName']
        ];

        return redirect(route('users.register.get'))->with('user', $userData);
    }


  /**
   * logs in the user by email
   *
   * @param $userData
   * @throws ModelNotFoundException
   */
  public function loginUserByEmail($userData)
  {
     if (!$this->auth->attempt([ 'email' => $userData['email'], 'password' => $userData['password'] ]))
     {
       throw new ModelNotFoundException('Invalid Credentials !');
     }
  }

  /**
   * log in the user by phone
   *
   * @param $userData
   * @throws ModelNotFoundException
   */
  public function loginUserByPhone($userData)
  {
    if (!$this->auth->attempt([ 'phone' => $userData['login_phone'], 'password' => $userData['login_password'] ]))
    {
      throw new ModelNotFoundException('Invalid Credentials!');
    }

    auth()->user()->update([
      'last_login_at' => Carbon::now()
    ]);
  }

    /**
     * redirect to the social driver to get the user info
     *
     * @param $driver
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function getAuthorizationFirst($driver)
    {
      if($driver == 'google')
      {
        return Socialite::driver($driver)->scopes([
            'https://www.googleapis.com/auth/plus.me',
            'https://www.googleapis.com/auth/plus.profile.emails.read',
        ])->redirect();
      }
        return Socialite::driver($driver)->redirect();
    }


    /**
     * Method to create a new user using the form data
     *
     * @param $userData
     * @return mixed
     */
    public function registerUser($userData)
    {
      $user = $this->userRepo->createUser([
        'first_name'        => $userData['first_name'],
        'last_name'         => $userData['last_name'],
        'phone'             => $userData['phone'],
        'email'             => $userData['email'],
        'password'          => Hash::make($userData['password']),
        'mobile_otp'        => rand(1000, 9999),
        'last_login_at'     => new \DateTime(),
        'otp_created_at'    => new \DateTime()
      ]);

      return $user;
    }

    /**
     * retrieves the user data after social driver
     *
     * @param $driver
     * @return \Laravel\Socialite\Contracts\User
     */
    public function getSocialUser($driver)
    {
        return Socialite::driver($driver)->user();
    }

  /**
   * verifies the user
   *
   * @param $confirmation_code
   * @throws InvalidConfirmationCodeException
   */
  public function verifyUser($inputs, $user)
  {
    if(! $inputs['code'] || !isset($inputs['code']))
    {
      throw new InvalidConfirmationCodeException('OTP Code Missing');
    }

    if ($user->mobile_otp != $inputs['code'])
    {
      throw new InvalidConfirmationCodeException('Wrong OTP Code');
    }

    if($this->userRepo->checkIfUserOTPExpired($user))
    {
      throw new InvalidConfirmationCodeException('Sorry. OTP Expired.');
    }

    $this->userRepo->verifyUser($user);
  }


  private function checkIfUserDeniedPermission($inputs)
  {
    if(isset($inputs['error']) && $inputs['error']) return true;

    return false;
  }

}