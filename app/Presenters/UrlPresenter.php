<?php

namespace App\Presenters;


use App\Model;

class UrlPresenter
{
  protected $model;

  private $options;
  private $model_name;
  private $name_prefix;


  protected function __construct($model, $options = [])
  {
    $this->model = $model;

    $this->options = $options;

    $this->initialize();
  }

  private function initialize() {
    if( ! $this->model_name) {
      $this->model_name = strtolower(class_basename($this->model));
    }

    if($name_prefix = $this->options['name_prefix'] ?? $this->model_name) {
      $this->setPrefix($name_prefix);
    }
  }

  protected function setPrefix(string $prefix){
    $this->name_prefix = "{$prefix}.";
  }

  protected function routeFor($name, array $params = []) {
    $route_params = [];

    foreach ($params as $key => $value) {
      if(is_numeric($key) && $value instanceof Model) {
        $key = strtolower(class_basename($value));
      }
      $route_params[$key] = $value;
    }

    return route($this->name_prefix . $name, $route_params);
  }


}