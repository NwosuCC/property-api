<?php

namespace App\Presenters;

use App\Model;
use App\Category;
use App\User;

class HouseUrlPresenter extends ModelUrlPresenter
{

  public function index_filters($category)
  {
    switch (true) {
      case !!($category) : {
        return $this->index_category($category); break;
      }
      default : {
        return $this->index();
      }
    }
  }


  public function index_category(Category $category)
  {
    return $this->routeFor('category', [$category]);
  }


  public function applied()
  {
    return $this->routeFor('applied');
  }


  public function assign(User $user)
  {
    return $this->routeFor('assign', [$user, $this->model]);
  }

}