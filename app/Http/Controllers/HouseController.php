<?php

namespace App\Http\Controllers;

use App\Http\Requests\HouseActionsRequest;
use App\User;
use App\House;
use App\Category;
use App\Events\HouseSaved;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Requests\HouseRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/*============================
 | A D M I N
 *------------------------*/
class HouseController extends Controller
{

  public function index(Category $category = null)
  {
    $houses = House::latest()->in($category)->count()->get();

    $categories = Category::latest()->get();

    return view(
      'house.index', compact('houses', 'categories', 'category')
    );
  }


  public function create()
  {
    $categories = Category::latest()->get();

    return view('house.create', compact('categories'));
  }


  public function store(HouseRequest $request)
  {
    if($this->userCant('create', House::class)){
      return redirect()->back();
    }

    /** @var Category $category */
    $category = Category::find( $request->{'category'});

    $house = $category->addHouse($request, $house = House::instance());

    event(new HouseSaved($house));

    set_flash('New house saved');

    return redirect()->route('house.index');
  }


  public function show(House $house)
  {
    $house = $house->withUserGroups()->first();

    return view('house.show', compact('house'));
  }


  public function edit(House $house)
  {
    if($this->userCant('update', $house)){
      return redirect()->back();
    }

    return view(
      'house.edit', ['house' => $house, 'categories' => Category::all()]
    );
  }


  public function update(HouseRequest $request, House $house)
  {
    $this->authorize('update', $house);

    $updated_house = Category::find($request->{'category'})->addHouse($request, $house);

    $house->getChanges()
      ? set_flash('House updated')
      : set_flash('No changes made', 'info');

    return redirect()->route('house.show', ['house' => $updated_house ]);
  }


  public function destroy(HouseRequest $request, House $house)
  {
    $this->authorize('delete', $house);

    $house->delete();

    set_flash('House deleted');

    return redirect()->route('house.index');
  }


  public function applied()
  {
    $houses = House::query()->applied()->get();

    return view('house.applied', compact('houses'));
  }


  public function assign(HouseActionsRequest $request, User $user, House $house)
  {
    $this->authorize('update', $house);

    if($assignable_house = $house->getAssignable()){
      $house = $assignable_house;

      if($request->{'action'} === House::ACTION_APPROVE){

        $house->approveFor($user, $request->{'expires_at'});
        set_flash(House::SUCCESS_APPROVED);
      }
      else{
        $house->declineFor($user);
        set_flash(House::SUCCESS_DECLINED);
      }
    }
    else {
      set_flash($house->getError(), 'danger');
    }

    return redirect()->back();
  }


  public function release(HouseActionsRequest $request, User $user, House $house)
  {
    $this->authorize('update', $house);

    $house->releaseFrom($user)
      ? set_flash('House released')
      : set_flash($house->getError(), 'danger');

    return redirect()->back();
  }


  protected function userCant(string $act_on, $object)
  {
//    if(can($act_on, $object))
    if($cannot = auth()->user()->cant($act_on, $object)) {
      set_flash(
        'You are not authorized to perform this action', 'danger'
      );
    }
    return $cannot;
  }


}
