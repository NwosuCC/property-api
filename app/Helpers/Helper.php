<?php

use App\Model;
use Illuminate\Support\Str;


if (! function_exists('can')) {
  /**
   * Indicates if an agent is authorized for an action
   * Used especially in views
   *
   * @param   string $act_on        The action to authorize
   * @param   string|Model $object  The model to modify
   * @return  string
   */
  function can(string $act_on, $object)
  {
    return auth()->user()->can($act_on, $object);
  }
}

if (! function_exists('errors_json')) {
  /**
   * Convert laravel messageBag to json string
   *
   * @param   object  $errors The messageBag instance
   * @return  string
   */
  function errors_json($errors)
  {
    $errorBag = [];

    $bagsKeys = array_keys( $errors->getBags() );

    foreach ($bagsKeys as $bagKey) {
      $bag = $errors->getBag($bagKey)->__toString();

      $errors_str = str_replace(
        '["', '"', str_replace('"]', '"', $bag)
      );

      $errorBag[$bagKey] = json_decode($errors_str);
    }

    if(count($bagsKeys) === 1 && $bagsKeys[0] === 'default'){
      $errorBag = array_shift($errorBag);
    }

    return json_encode($errorBag);
  }
}

if (! function_exists('get_flash')) {
  /**
   * Retrieve a previously set flash message for display
   *
   * @return array
   */
  function get_flash()
  {
    if(session('message')) {
      $flash = explode('|', session()->pull('message'));

      return array_combine( ['message', 'type'], array_pad($flash, 2, ''));
    }

    return [];
  }
}

if (! function_exists('model')) {
  /**
   * Returns a model class instance
   *
   * @param  string $class_name
   * @return Model
   */
  function model(string $class_name)
  {
    return app()->make($class_name);
  }
}

if (! function_exists('set_flash')) {
    /**
     * Set a flash message with the alert type
     *
     * @param  string  $message The message to display
     * @param  string  $type    'danger' | 'warning' | 'info' | 'success'
     *
     * @return void
     */
    function set_flash($message, $type = 'success')
    {
        session()->flash('message', trim($message) . '|' . trim($type));
    }
}

if (! function_exists('str_words')) {
  /**
   * Limit the number of words in a string
   *
   * @param  string  $value
   * @param  int  $words
   * @param  string  $end
   *
   * @return string
   */
  function str_words($value, $words = 100, $end = '...')
  {
    return Str::words($value, $words, $end);
  }
}

if (! function_exists('user_slug')) {
  /**
   * Generate the slug for the authenticated user's name
   *
   * @param  string  $name
   *
   * @return string
   */
  function user_slug($name = '')
  {
    if(!$name && !auth()->check()){
      return '';
    }

    return str_slug( $name ?: auth()->user()->name );
  }
}
