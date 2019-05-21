<?php

namespace App\Http\Controllers;

use App\User;
use App\House;
use App\Category;
use App\Events\HouseSaved;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Http\Requests\HouseRequest;

/*============================
 | A D M I N
 *------------------------*/
class HouseController extends Controller
{
  public function index(Category $category = null)
  {
    $houses = House::latest()->in($category)->count()->get();

    $categories = Category::latest()->get();

    return view('house.index', compact('houses', 'categories', 'category'));
  }


  public function create() {
    $categories = Category::latest()->get();

    return view('house.create', compact('categories'));
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


  public function store(HouseRequest $request)
  {
    if(auth()->user()->cant('create', House::class)) {
      // ToDo: customize error messages class
      set_flash('You are not authorized to perform this action', 'danger');
      return redirect()->back();
    }

    $category = Category::find( $request->input(['category']) );

    $house = House::instance()->fill([
      'title' => $request->input('title'),
      'description' => $request->input('description'),
      'slug' => Str::slug( $request->input('title') )
    ]);

    $category->houses()->save($house);

    event(new HouseSaved($house));

    set_flash('New house saved');

    return redirect()->route('house.index');
  }


  public function edit(House $house) {
    if(auth()->user()->cant('update', $house)) {
      // ToDo: customize error messages class
      set_flash('You are not authorized to perform this action', 'danger');
      return redirect()->back();
    }

    $categories = Category::latest()->get();

    return view('house.edit', compact('categories', 'house'));
  }


  public function update(HouseRequest $request, House $house)
  {
    $this->authorize('update', $house);

    $category = Category::find($request->input('category'));

    $house->fill([
      'title' => $request->input('title'),
      'description' => $request->input('description'),
      'slug' => Str::slug( $request->input('title') ),
    ]);

    auth()->user()->createHouse( $category, $house );

    [$message, $type] = $house->getChanges() ? ['House updated', ''] : ['No changes made', 'info'];

    set_flash($message, $type);

    return redirect()->route('house.show', ['house' => $house ]);
  }


  public function destroy(HouseRequest $request, House $house) {
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


  public function assign(Request $request, User $user, House $house)
  {
    if( ! $assign_action = $request->input('assign')) {
      set_flash('Invalid Action! Please, try again', 'danger');
    }
    else if($rented = $house->tenants()->first()){
      set_flash(House::ERROR_RENTED, 'danger');
    }

    if($assign_action && empty($rented)){
      $house = $house->where('id', $house->id)->with('applicants')->first();

      if($assign_action === House::ACTION_APPROVE){
        // Approve
        // ToDo: add this to Approval Form
//        $expires_at = $request->input('expires_at');
        // ToDo: remove this simulation
        $expires_at = Carbon::now()->addMonth(random_int(6, 30));

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

    return redirect()->back();
  }


}
