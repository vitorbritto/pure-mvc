<?php

spl_autoload_register(function($class) {
  $file = str_repeat('\\', '/', $class);

  if (file_exists($file)) {
    require_once $file;
  }
});