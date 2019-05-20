<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use App\Presenters\UserUrlPresenter;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  use HasApiTokens, Notifiable, SoftDeletes;


  const UID_LENGTH = 11;

  protected $fillable = [
    'name', 'email', 'password', 'uid'
  ];

  protected $hidden = [
    'password', 'remember_token',
  ];


  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);

    // A new user has role 'Applicant' by default, before graduating to 'Tenant'
//    User::created(function($user){
//      $user->roles()->attach( Applicant::role() );
//    });
  }


  /*===============================
   | M O D E L   M E T H O D S
   *----------------------------*/
  /** @return User */
  public static function model() {
    return app()->make(static::class);
  }

  public function getRouteKeyName() {
    return 'uid';
  }

  public function getRouteAttribute() {
    return new UserUrlPresenter($this);
  }


  /*===============================
   | R O L E   M E T H O D S
   *----------------------------*/
  public function roles() {
    return $this->belongsToMany(Role::class)->withTimestamps();
  }

  /**
   * @param string $role
   * @return Role
   */
  public function hasRole(string $role) {
    $roles = $this->exists ? $this->roles() : Role::query();

    return $roles->filter($role)->first();
  }

  public function isAdmin() {
    return (bool) $this->hasRole(Role::ADMIN);
  }


  /*================================================
   | P R O D U C T   A D M I N   M E T H O D S
   *--------------------------------------------*/
  public function categories() {
    return $this->hasMany(Category::class);
  }

  public function createCategory(Category $category) {
    $category->user_id = $this->id;
    $this->categories()->save($category);
  }

  public function createHouse(Category $category, House $house){
    $this->tenancies()->save( $category->addHouse($house) );
  }

  public function scopeTenants($query){
    return $query->has('tenancies')->withCount(['tenancies']);
  }

  public function scopeApplicants($query){
    return $query->has('applications')->withCount(['applications']);
  }

  public function scopeWithHouses($query){
    return $query->with('houses')->withCount(['houses']);
  }


  /*=============================================
   | P R O D U C T   U S E R   M E T H O D S
   *-----------------------------------------*/
  public function houses() {
    return $this->belongsToMany(House::class)
      ->withPivot('expires_at')
      ->withTimestamps()
      ->wherePivot('deleted_at', null);
  }

  public function tenancies() {
    return $this->houses()->wherePivot(...House::whereRented());
  }

  public function applications() {
    return $this->houses()->wherePivot(...House::whereAvailable());
  }


  /*===============================
   | A P I   A U T H   M E T H O D S
   *----------------------------*/
  public static function tokenName() {
    return env('APP_TOKEN_NAME') ?? 'Personal Token';
  }


}
