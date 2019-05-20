<?php

namespace App\Presenters;


class UserUrlPresenter extends ModelUrlPresenter
{
  public function __construct($model, array $options = [])
  {
    parent::__construct($model, $options);
  }


  // For routes under name prefix 'applicant'
  // E.g in view(applicant.index), $applicant->route->applicant->show => /applicants/<user-id>
  public function applicant()
  {
    $this->setPrefix('applicant');
    return $this;
  }


  public function tenant()
  {
    $this->setPrefix('tenant');
    return $this;
  }

}