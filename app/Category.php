<?php

namespace App;

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

    public function addHouse(House $house) {
        $house->{'category_id'} = $this->{'id'};
        return $house;
    }


}
