<?php

namespace App\Http\Controllers\Api;


use JWTAuth;
use App\Events\UserWasRegistered;
use Bueno\Transformers\UserTransformer;
use Bueno\Authenticate\AuthenticateUser;
use Bueno\Repositories\DbUserRepository;
use Bueno\Validations\CreateUserValidator;
use Bueno\Validations\ValidationException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Bueno\Exceptions\ModelNotFoundException;
use Bueno\Validations\ResetPasswordValidator;

/**
 * Class ClientsController
 * @package App\Http\Controllers\Api
 */
class AuthController extends ApiController {

  /**
   * calls parent constructor and call jwt.auth middleware
   */
  function __construct(
      CreateUserValidator $createUserValidator,
      AuthenticateUser $authenticate,
      DbUserRepository $userRepo,
      ResetPasswordValidator $resetPasswordValidator)
  {
    parent::__construct();

    $this->userRepo = $userRepo;
    $this->createUserValidator = $createUserValidator;
    $this->resetPasswordValidator = $resetPasswordValidator;
    $this->authenticate = $authenticate;
  }

  /**
   * Return a JWT ( JSON Web Token )
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function authenticate()
  {
    $credentials = request()->only('phone', 'password');

    try
    {
      // verify the credentials and create a token for the user
      if (! $token = JWTAuth::attempt($credentials))
      {
        return $this->errorUnauthorized('Invalid Credentials');
      }
    }
    catch (JWTException $e)
    {
      // something went wrong
      return $this->setStatusCode(500)->respondWithErrors('Sorry, We could not create token.');
    }

    // if no errors are encountered we can return a JWT
    return $this->respondWithArray(['token' => $token ]);
  }

  /**
   * register user and returns the access token
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function postRegister()
  {
    $inputs = request()->all();

    try
    {
      $this->createUserValidator->fire($inputs);
    }
    catch(ValidationException $e)
    {
      return $this->respondWithErrors($e->getMessages());
    }

    $user = $this->authenticate->registerUser($inputs);

    event(new UserWasRegistered($user));

    $token = JWTAuth::attempt(request()->only('email', 'password'));

    return $this->respond(compact('token'));
  }

  /**
   * send otp code to user to change the password
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function postForgotPassword()
  {
    $inputs  = request()->all();

    $inputs['type'] = 1;

    try
    {
      $this->userRepo->checkIfUserExistsByColumn('phone', $inputs['phone']);
    }
    catch(ModelNotFoundException $e)
    {
      return $this->respondWithErrors($e->getMessage());
    }

    $this->userRepo->sendForgotPasswordOTP($inputs);

    return $this->respond([
      'message' => 'Forgot Password OTP Sent !',
    ]);
  }

  /**
   * reset password of the user
   *
   * @return \Illuminate\Http\JsonResponse
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
      return $this->respondWithErrors($e->getMessage());
    }
    catch(ValidationException $e)
    {
      return $this->respondWithErrors($e->getMessages());
    }

    return $this->respond([
      'message' => 'You password has been reset. You can now login with the new password'
    ]);
  }

  /**
   * resend OTP for forgot password
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function postResendOTP()
  {
    $inputs = request()->all();

    try
    {
      $this->userRepo->resendForgotPasswordOTP($inputs);
    }
    catch(ValidationException $e)
    {
      return $this->respondWithErrors($e->getMessage());
    }
    catch(ModelNotFoundException $e)
    {
      return $this->respondWithErrors($e->getMessage());
    }

    return $this->respond([
      'message' => "OTP Code Sent !"
    ]);
  }
}