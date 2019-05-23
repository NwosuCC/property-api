@php
  // Expected Array sample:  [
  //   'model' => $house, ,
  //   'view' => 'house.show',
  //   'url_index' => $user->route->applicant->index
  //   'text_index' => $user->route->applicant->index
  // ]

  // The url to the index of this model => ['/houses', '/categories/, '/tenants/, etc]
  //if(empty($url_index)){
    try {
      $url_index = $model->route->index;
    }catch (\Exception $e){
      $url_index = app()->make('App\House')->route->index;
    }
  //}

  // The text to display for the index url
  //if(empty($text_index)){
    $text_index = 'Home';
  //}

  // The url to the previous page
  $url_back = url()->previous();

  // Display only $url_back if both $url_index and $url_back are same
  $not_sane = $url_back !== $url_index;

  // Prepends the double-left-arrow to the $url_back
  $arrow = $not_sane ? '' : '<< ';

  // Every page may have these crumbs
  $defaults = [
    ['text' => $text_index,       'url' => $url_index,  'bl' => $not_sane ? 0 : -1],
    ['text' => $arrow . 'Back',   'url' => $url_back,   'bl' => $not_sane],
  ];


  // An array of bread crumbs for each page. The key is passed in as the $view variable
  // 'bl' (border-left) indicates if a left-border '|' is prepended to the crumb-text
  switch(strval($view)){
    case 'house.show' : {
      $items = [
        ['text' => 'Edit',            'url' => $house->route->edit,     'bl' => can('update', $model) ? 1 : -1  ],
        ['text' => 'Delete',          'url' => $house->route->delete,   'bl' => can('delete', $model) ? 1 : -1  ]
      ]; break;
    }
    case 'house.edit' : {
      $items = [
        ['text' => 'Delete',          'url' => $house->route->delete,   'bl' => can('delete', $model) ? 1 : -1  ]
      ]; break;
    }
    case 'applicant.index' : {
      $items = [
        ['text' => 'Applicants',      'url' => $user->route->applicant->index]
      ]; break;
    }
    case 'tenant.index' : {
      $items = [
        ['text' => 'Tenants',         'url' => $user->route->tenant->index]
      ]; break;
    }
    default : {
      $items = [];
    }
  };

  $items = array_merge($defaults, $items);
@endphp


{{--  Display the  bread crumbs in iteration --}}
@each('snippets.bread-crumb.item', $items, 'crumb')
