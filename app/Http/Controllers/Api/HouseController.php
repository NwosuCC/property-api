<?php

namespace App\Http\Controllers\Api;

use App\Applicant;
use App\House;
use App\Category;
use App\Http\Controllers\Controller;
use App\User;


class HouseController extends Controller
{

    public function index(Category $category = null)
    {
        $houses = House::latest()->available()->in($category)->get();

        return response()->json($houses);
    }


    public function show(House $house)
    {
      return response()->json($house);
    }


    public function apply(House $house)
    {
      if($house->tenants()->first()){
        return response()->json([
          'house' => $house->title, 'error' => $house->errorRented()
        ]);
      }

      /** @var Applicant $user */
      $user = auth()->user();

      $user->applications()->attach($house);
      return response()->json( $house );
    }


}
