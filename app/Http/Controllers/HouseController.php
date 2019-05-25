<?php

namespace App\Http\Controllers;

use App\Http\Requests\HouseAssignRequest;
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
    $house = House::query()
      ->where('id', $house->id)
      ->with('tenants')
      ->with('applicants')
      ->first();

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

    $house = Category::find( $request->{'category'})->addHouse($request, $house);

    [$message, $type] = $house->getChanges() ? ['House updated', ''] : ['No changes made', 'info'];

    set_flash($message, $type);

    return redirect()->route('house.show', ['house' => $house ]);
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


  public function assign(HouseAssignRequest $request, User $user, House $house)
  {
    if($request->{'expires_at'}){
      $expires_at = Carbon::parse($request->{'expires_at'});
    }

    $house = $house->where('id', $house->id)->with('tenants', 'applicants')->first();

    if($rented = $house->tenants->count()){
      // For Modal Dialog
      abort(400, House::ERROR_RENTED);
    }
    else {
      if($request->{'action'} === House::ACTION_APPROVE){
        // Approve
        $user->tenancies()->attach($house, compact('expires_at'));
        set_flash('House approved');

        $house->applicants->each(function($user) use($house){
          $user->applications()->detach($house);
        });
      }
      else{
        // Decline
        $user->applications()->detach($house);
        set_flash('House declined');
      }
    }

    // For Modal Dialog
    return response()->json(['message' => 'House declined'], 200);
  }


  public function release(Request $request, User $user, House $house)
  {
    $action = $request->input('action');
    $valid_action = $action === House::ACTION_RELEASE;

    $relation = $user->tenancies()->wherePivot('house_id', $house->id);
    $house = $relation->first();

    if( ! $valid_action) {
      set_flash('Invalid Action! Please, try again', 'danger');
    }
    else if($not_expired = ! $house->{'is_expired'}){
      set_flash(House::ERROR_NOT_EXPIRED, 'danger');
    }

    if($valid_action && empty($not_expired)){
      // Release
      $relation->updateExistingPivot($house->id, ['deleted_at' => Carbon::now()]);
      // Also works: $house->pivot->update(['deleted_at' => Carbon::now()]);
      set_flash('House released');
    }

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
