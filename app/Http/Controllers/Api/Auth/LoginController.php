<?php

namespace App\Http\Controllers\Api\Auth;

use App\User;
use App\Http\Controllers\Controller;


class LoginController extends Controller
{

  public function login()
  {
    if(auth()->attempt(request(['email', 'password']))){

      $user = auth()->user();

      $user->token = $user->createToken(User::tokenName())->accessToken;

      return response()->json($user, 200);
    }

    return abort(401, "Incorrect username or password");
  }
}
