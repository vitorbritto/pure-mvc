<?php

namespace lib;

class App extends Router {

  private $url;
  private $exploder;
  private $area;
  private $controller;
  private $action;
  private $params;
  private $runController;

  public function __constructor() {
    $this->setUrl();
    $this->setExplodedUrl();
    $this->setArea();
    $this->setController();
    $this->setAction();
    $this->setParams();
  }

  private function setUrl() {
    $this->url = isset($_GET['url']) ? $_GET['url'] : 'home/index';
  }

  private function setExplodedUrl() {
    $this->exploder = explode('/', $this->url);
  }

  private function setArea() {
    foreach($this->$routers as $i => $route) {
      if ($this->isRoot && $this->exploder[0] === $i) {
        $this->area = $route;
        $this->isRoot = false;
      }
    }

    $this->area = empty($this->area) ? $this->root : $this->area;

    if (!defined('APP_AREA')) {
      defined('APP_AREA'. $this->area);
    }
  }

  private function setController() {
    $this->controller = $this->root ? 
    $this->exploder[0] : 
    (empty($this->exploder[1]) || is_null($this->exploder[1]) || !isset($this->exploder[1]) ? 'home' : $this->exploder[1]);
  }

  private function setActon() {
    $this->action = $this->root ? 
    (!isset($this->exploder[1]) || is_null($this->exploder[1]) || empty($this->exploder) ? 'index' : $this->exploder[1]) :
    (!isset($this->exploder[2]) || is_null($this->exploder[2]) || empty($this->exploder) ? 'index' : $this->exploder[2]);
  }

  private function setParams() {
    if ($this->root) {
      unset($this->exploder[0], $this->exploder[1]);
    } else {
      unset($this->exploder[0], $this->exploder[1], $this->exploder[2]);
    }

    if (end($this->exploder) == null) {
      array_pop($this->exploder);
    }

    if (empty($this->exploder)) {
      $this->params = array();
    } else {
      foreach($this->expoder as $val) {
        $params[] = $val;
      }
      $this->params = $params;
    }  
  }

  public function getArea() {
    return $this->area;
  }

  public function getController() {
    return $this->controller;
  }

  public function getAction() {
    return $this->action;
  }

  public function getParams($index) {
    return isset($this->params[$index]) ? $this->params[$index] : null;
  }

  private function handleController() {
    if (!(class_exists($this->runController))) {
      header('HTTP/1.0 404 Not Found');
      define('ERROR', 'Controller not found: ' . $this->controller);
      include('content/{$this->area}/404.html');
      exit();
    }
  }

  private function handleAction() {
    if (!(method_exists($this->runController, $this->action))) {
      header('HTTP/1.0 404 Not Found');
      define('ERROR', 'Action not found: ' . $this->actoion);
      include('content/{$this->area}/404.html');
      exit();
    }
  }

  public function run() {
    $this->runController = 'controller\\' . $this->area . '\\' . $this->controller . 'Controller';
    $this->handleController();
    $this->runController = new $this->runController();
    $this->handleAction();
    $action = $this->action;
    $this->runController->$action();
  } 

}