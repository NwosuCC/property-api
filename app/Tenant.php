<?php

namespace App;

class Tenant extends User
{
  protected static $role = Role::TENANT;


  public static function users()
  {
//    return User::model()->havingRole(static::role());
    return static::role()->users();
  }


  /** @return Role */
  public static function role() {
    return Role::instance()->filter(static::$role)->first();
  }

}
