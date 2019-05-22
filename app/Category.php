<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'name', 'slug', 'description'
    ];


    public function getRouteKeyName() {
        return 'slug';
    }


    public function user() {
        return $this->belongsTo(User::class);
    }


    public function scopeIn($query, $category = null){
      return $category ? $query->where('category_id', $category->id) : null;
    }

    public function houses() {
        return $this->hasMany(House::class)->latest();
    }

    public function addHouse(Request $request, House $house)
    {
      $house
        ->fill( $request->only(['title', 'description']))
        ->setAttribute('slug', Str::slug( $house->{'title'} ));

      $this->houses()->save($house);

      return $house;
    }


}
