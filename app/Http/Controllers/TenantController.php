<?php

namespace App\Http\Controllers;

use App\User;

class TenantController extends Controller
{

  public function __construct()
  {
    $this->middleware(['auth', 'admin']);
  }


  public function index()
  {
    $tenants = User::query()->tenants()->get();

    return view('tenant.index', compact('tenants'));
  }


  public function show(User $user)
  {
    $tenancies = $user->tenancies()->get();

    return view('tenant.show', compact('user', 'tenancies'));
  }

}
