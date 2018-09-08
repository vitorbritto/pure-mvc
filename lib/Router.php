<?php

namespace lib;

class Router {
  protected $routers = array(
    'site'  => '',
    'admin' => 'admin'
  );

  protected $root = 'site';
  protected $isRoot = true;
}