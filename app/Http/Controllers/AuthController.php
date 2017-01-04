<?php namespace App\Http\Controllers;

use App\Events\UserWasRegistered;
use Bueno\Exceptions\InvalidConfirmationCodeException;
use Flash;
use Bueno\Mailers\UserMailer;
use Bueno\Repositories\DbUserRepository;
use Bueno\Validations\ResetPasswordValidator;
use App\Http\Controllers\Controller;
use Bueno\Repositories\DbCommonRepository;
use Bueno\Authenticate\AuthenticateUser;
use Bueno\Validations\CreateUserValidator;
use Bueno\Validations\ValidationException;
use Bueno\Exceptions\ModelNotFoundException;

/**
 * Class AuthController
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{

  /**
   * Where to redirect users after login / registration.
   *
   * @var string
   */
  public $redirectTo = '/';

  /**
   * @var AuthenticateUser
   */
  protected $authenticate;

  /**
   * @var CreateUserValidator
   */
  protected  $validator;

  /**
   * @var DbUserRepository
   */
  protected $userRepo;

  /**
   * @var ResetPasswordValidator
   */
  protected  $resetPasswordValidator;
  /**
   * Create a new authentication controller instance.
   *
   * @return void
   */
  public function __construct(DbCommonRepository $commonRepo, AuthenticateUser $authenticate, CreateUserValidator $validator, DbUserRepository $userRepo, ResetPasswordValidator $resetPasswordValidator)
  {
    $this->userRepo = $userRepo;

    $this->validator = $validator;

    $this->commonRepo = $commonRepo;

    $this->authenticate = $authenticate;

    $this->resetPasswordValidator = $resetPasswordValidator;

    $this->middleware('auth', ['only' => ['getVerify', 'postVerify', 'logout' ]]);
    
    $this->middleware('guest', ['only' => 'getLogin', 'getRegister']);
  }


  /**
   * shows the login form
   *
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function getLogin()
  {
    $page = 'login';

    return view('users.login', compact('page'));
  }

  /**
   *  logs in the user into the app
   *
   * @return $this
   */
  public function postLogin()
  {
    $inputs = request()->all();

    try
    {
      $this->authenticate->loginUserByPhone($inputs);
    }
    catch(ModelNotFoundException $e)
    {
      Flash::danger($e->getMessage());

      return redirect(route('users.login.get'))->withInput();
    }

    if(auth()->user()->group->name != 'User')
    {
      return redirect()->route('admin.home');
    }

    return redirect()->route('items.search.xprs-menu.get');
  }

  /**
   * shows the register form
   *
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function getRegister()
  {
    $page = 'register';

    return view('users.register', compact('page'));
  }

  /**
   * validates and register user
   *
   * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function postRegister()
  {
    $inputs = request()->all();

    try
    {
      $this->validator->fire($inputs);
    }
    catch(ValidationException $e)
    {
      return redirect(route('users.register.get'))->withInput()->withErrors($e->getErrors());
    }

    $user = $this->authenticate->registerUser($inputs);

    auth()->login($user, true);

    event(new UserWasRegistered(auth()->user()));

    return redirect(route('users.verify.get') . '?postRegister=1');
  }

  /**
   * handles social login. If user does not exist, redirect it to the
   * register page with pre-filled values, else login him.
   *
   * @param $social
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function social($social)
  {
    $inputs = request()->all();

    $code = request()->has('code') ? request()->get('code') : null;

    return $this->authenticate->execute($social, $code, $inputs);
  }

  /**
   * log outs the user
   *
   * @return \Illuminate\Http\RedirectResponse
   */
  public function logout()
  {
    auth()->logout();

    return redirect()->route('users.login.get');
  }

  /**
   * shows the forgot password form
   *
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function getForgotPassword()
  {
    return view('users.forgot_password');
  }

  /**
   * send otp code to user to change the password
   *
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function postForgotPassword()
  {
    $inputs  = request()->all();

    try
    {
      $this->userRepo->checkIfUserExistsByColumn('phone', $inputs['phone']);
    }
    catch(ModelNotFoundException $e)
    {
      Flash::danger($e->getMessage());

      return redirect(route('users.forgot_password.get'));
    }

    $this->userRepo->sendForgotPasswordOTP($inputs);

    Flash::success('Forgot Password OTP Sent !');

    return redirect(route('users.reset_password.get'))->with('phone', $inputs['phone']);
  }

  /**
   * shows reset password form
   *
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function getResetPassword()
  {
    if(session()->has('phone') || old('phone'))
    {
      return view('users.reset_password');
    }

    return redirect(route('users.forgot_password.get'));

  }

  /**
   *  validates the form and resets the user password
   *
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function postResetPassword()
  {
    $inputs = request()->all();

    try
    {
      $this->resetPasswordValidator->fire($inputs);

      $this->userRepo->resetUserPassowrd($inputs);
    }
    catch(ModelNotFoundException $e)
    {
      Flash::danger($e->getMessage());

      return redirect(route('users.reset_password.get'))->withInput();
    }
    catch(ValidationException $e)
    {
      return redirect(route('users.reset_password.get'))->withInput()->withErrors($e->getErrors());
    }

    Flash::success('You password has been reset. You can now login with the new password');

    return redirect(route('users.login.get'));
  }

  /**
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function getVerify()
  {
    $seo = $this->commonRepo->getSeoBySlug('verify-user');

    if($seo) $this->setSeo($seo->meta_title, $seo->meta_description,  $seo->meta_image_url);

    if(auth()->user()->mobile_verify == 1)
    {
      return redirect(route('users.account.get'));
    }
    return view('users.verify');
  }

  /**
   * verifies the user
   *
   * @param $confirmation_code
   * @return mixed
   * @throws InvalidConfirmationCodeException
   */
  public function postVerify()
  {
    $inputs = request()->all();

    try
    {
      $this->authenticate->verifyUser($inputs, auth()->user());
    }
    catch(InvalidConfirmationCodeException $e)
    {
      Flash::danger($e->getMessage());

      return redirect()->route('users.verify.get');
    }

    Flash::success('You have successfully verified your account.');

    return redirect()->route('items.search.xprs-menu.get');
  }

  public function getResend()
  {
    $this->userRepo->resendOTPCode(auth()->user());

    Flash::success("Code Resent");

    return redirect()->back();
  }

  public function resendForgotPasswordOTP()
  {
    $inputs = request()->all();

    $this->userRepo->resendForgotPasswordOTP(request()->all());

    Flash::success("OTP Code Sent");

    return redirect(route('users.reset_password.get'))->with('phone', $inputs['phone']);
  }

  public function emailTest($email)
  {
    $mailer = new UserMailer;
    $mailer->testMail($email);
  }

}
