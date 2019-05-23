<?php

namespace App;


use App\Events\HouseSaved;
use App\Presenters\HouseUrlPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;


class House extends Model
{
  protected $fillable = [
    'title', 'description', 'slug', 'status'
  ];

  protected $casts = [
    // De-serializes a database serialized JSON column 'options' to PHP array
    // 'options' => 'array',
  ];

  protected $prepend = ['route'];

  protected $dates = ['deleted_at'];

  protected $appends = ['status', 'is_rented', 'is_expired'];

  protected $events = [
    'created' => HouseSaved::class,
    'updated' => HouseSaved::class,
    'deleting' => HouseSaved::class,
    'deleted' => HouseSaved::class,
  ];

  const STATUS_AVAILABLE = '1';
  const STATUS_RENTED = '2';

  protected $states = [
    self::STATUS_AVAILABLE => 'Available',
    self::STATUS_RENTED => 'Rented',
  ];

  const ERROR_RENTED = 'House is no longer available';
  const ERROR_NOT_EXPIRED = 'House is not yet expired';

  const ACTION_APPROVE = 'approve';
  const ACTION_DECLINE = 'decline';
  const ACTION_RELEASE = 'release';


  protected static function boot() {
    parent::boot();

//    static::addGlobalScope('category', function (Builder $builder){
//      $builder->whereIn('category_id', Category::pluck('id'));
//    });
  }


  /*===============================
  | M O D E L   M E T H O D S
  *----------------------------*/
  public function getRouteKeyName(){
    return 'slug';
  }

  public function getRouteAttribute(){
    return new HouseUrlPresenter($this, ['name_prefix' => 'house']);
  }

  public function getIsRentedAttribute(){
    return (bool) $this->tenants->count();
  }

  public function getStatusAttribute(){
    $state = $this->is_rented ? self::STATUS_RENTED : self::STATUS_AVAILABLE;

    return $this->states[ $state ];
  }

  public function getIsExpiredAttribute(){
    return ($expiry_date = $this->expires_at) and $expiry_date->isPast();
  }

  public function getExpiresAtAttribute(){
    return $this->pivot ? Carbon::parse($this->pivot->expires_at) : null;
  }

  // ToDo: write a wrapper to make the parts consistent
  public function getExpiresAtDiffAttribute(){
    if($expiry = $this->expires_at){
      /** @var Carbon $expiry */
      $parts = $expiry->diffInHours() > 0 ? 2 : 1;

      return $expiry->diffForHumans(null, true, true, $parts);
    }

    return null;
  }

  // For House Approval/Decline Modal
  public function getActionParams(User $user, string $action) {
    switch (strval($action)){
      case 'approve' :
      case 'decline' : {
        $route = $this->route->assign($user); break;
      }
      case 'release' : {
        $route = $this->route->release($user); break;
      }
      default : $route = '';
    }

    return json_encode([
      'house' => $this->title,
      'user' => $user->name,
      'route' => $route
    ]);
  }


  /*==========================================================================================
   | H O U S E - C A T E G O R Y   ::   QUERY SCOPES AND RELATIONS
   *--------------------------------------------------------------------------------------*/
  public function scopeIn($query, $category = null){
    return $category ? $query->where('category_id', $category->id) : null;
  }

  public function category(){
    return $this->belongsTo(Category::class);
  }


  /*================================================
   | H O U S E - U S E R   ::   QUERY SCOPES
   *---------------------------------------------*/
  public static function whereRented(){
    return ['expires_at', '!=', null];
  }

  public static function whereAvailable(){
    return ['expires_at', null];
  }

  public function scopeAvailable($query){
    return $query->applied()->orWhereDoesntHave('users');
  }

  protected function hasUsers($query, $user_group, $where){
    return $query
      ->whereHas($user_group, function ($q) use($where) { $q->where(...$where); })
//      ->whereHas('users', function ($q) use($where) { $aa = $q->where(...$where); dd($aa->toSql()); })
      ->count();
  }

  public function scopeRented($query){
    return $this->hasUsers($query, 'tenants', static::whereRented());
//    return $query->has('tenants')->withCount(['tenants']);
  }

  public function scopeApplied($query){
//    return $this->hasUsers($query, 'applicants', static::whereAvailable());
    return $query->has('applicants')->withCount(['applicants']);
  }

  public function scopeWithUsers($query){
    return $query->with('users');
  }

  public function scopeCount($query){
    return $query->withCount(['users']);
  }


  /*===========================================
   | H O U S E - U S E R   ::   RELATIONS
   *---------------------------------------*/
  public function users() {
    return $this->belongsToMany(User::class)
      ->withPivot('expires_at')
      ->withTimestamps()
      ->wherePivot('deleted_at', null);
  }

  public function tenants() {
    return $this->users()->wherePivot(...static::whereRented());
  }

  public function applicants() {
    return $this->users()->wherePivot(...static::whereAvailable());
  }


}
