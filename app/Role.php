<?php

namespace App;


class Role extends Model
{
    const ADMIN = 'admin';
    const TENANT = 'tenant';
    const APPLICANT = 'applicant';

    protected $fillable = [
        'name', 'slug', 'description'
    ];


    public function getRouteKeyName() {
        return 'slug';
    }


    public function scopeFilter($query, $role_slug){
      return $query->where($this->getRouteKeyName(), $role_slug);
    }


    public function users() {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

}
