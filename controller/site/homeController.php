<?php

namespace controller\site;

use lib\Controller;

class HomeController extends Controller {

  public function __constructor() {
    parent::__constructor();
    $this->layout = '_layout';
  }

  public function index() {
    $this->title = 'Home';
    $this->view();
  }

}