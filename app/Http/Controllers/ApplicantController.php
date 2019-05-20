<?php

namespace App\Http\Controllers;

use App\User;

class ApplicantController extends Controller
{

    public function __construct()
    {
      $this->middleware(['auth', 'admin']);
    }


    public function index()
    {
        $applicants = User::query()->applicants()->get();

        return view('applicant.index', compact('applicants'));
    }


    public function show(User $user)
    {
      $applications = $user->applications()->get();
//      dd($applications->first()->toArray());

      return view('applicant.show', compact('user', 'applications'));
    }


}
