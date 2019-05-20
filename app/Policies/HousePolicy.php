<?php

namespace App\Policies;

use App\User;
use App\House;
use Illuminate\Auth\Access\HandlesAuthorization;

class HousePolicy
{
    use HandlesAuthorization;

    /* NOTE:
     * Register Policy in AuthServiceProvider $policies
     *  'App\HouseTest' => 'App\Policies\HousePolicy',
     */

    /**
     * Determine whether to override all other checks for this user
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function before(User $user)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the house.
     *
     * @param  \App\User  $user
     * @param  \App\House  $house
     * @return mixed
     */
    public function view(User $user, House $house)
    {
      return false;
    }

    /**
     * Determine whether the user can create houses.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
      return false;
    }

    /**
     * Determine whether the user can update the house.
     *
     * @param  \App\User  $user
     * @param  \App\House  $house
     * @return mixed
     */
    public function update(User $user, House $house)
    {
      return false;
    }

    /**
     * Determine whether the user can delete the house.
     *
     * @param  \App\User  $user
     * @param  \App\House  $house
     * @return mixed
     */
    public function delete(User $user, House $house)
    {
      return false;
    }

    /**
     * Determine whether the user can restore the house.
     *
     * @param  \App\User  $user
     * @param  \App\House  $house
     * @return mixed
     */
    public function restore(User $user, House $house)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the house.
     *
     * @param  \App\User  $user
     * @param  \App\House  $house
     * @return mixed
     */
    public function forceDelete(User $user, House $house)
    {
        return false;
    }
}
