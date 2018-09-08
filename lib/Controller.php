<?php

namespace lib;

class Controller extends App {
  public $data;
  public $layout;
  private $path;
  private $pathRender;
  protected $title = null;
  protected $description = null;
  protected $keywords;
  protected $image;
  protected $captionController;
  protected $captionAction;
  protected $captionParams;

  public function __constructor() {
    parent::__constructor();
  }

  private function setPath() {
    if (is_array($render)) {
      foreach ($render as $li) {
        $path = 'view/' . $this->getArea() . '/' . $this->getController() . '/' . $li . '.php';
        $this->fileExists($path);
        $this->path[] = $path;
      }
    } else {
      $this->pathRender = is_null($render) ? $this->getAction() : $render;
      $this->path = 'view/' . $this->getArea() . '/' . $this->getController() . '/' . $this->pathRender . '.php';
      $this->fileExists($this->path);
    }
  }
  
  private function fileExists($file) {
    if (!file_exists($file)) {
      die('File not found ' . $file);
    }
  }

  public function view($render = null) {
    $this->title = is_null($this->title) ? 'Title' : $this->title;
    $this->description = is_null($this->description) ? 'Description' : $this->description;
    $this->keywords = is_null($this->keywords) ? 'Tags' : $this->keywords;

    $this->setPath($render);

    if (is_null($this->layout)) {
      $this->render();
    } else {
      $this->layout = 'content/{$this->getArea()}/partials/{$this->layout}.php';
      if ($file_exists($this->layout)) {
        $this->render($this->layout);
      } else {
        die('File not found');
      }
    }
  }

  public function render($file = null) {
    if (is_array($this->data) && count($this->data) > 0) {
      extract($this->data, EXTRACT_PREFIX_ALL, 'view');
      extract(array(
        'controller' => (is_null($this->captionController) ? '' : $this->captionController),
        'action' => (is_null($this->captionAction) ? '' : $this->captionAction),
        'params' => (is_null($this->captionParams) ? '' : $this->captionParams),
      ), EXTRACT_PREFIX_ALL, 'caption');
    }

    if (!is_null($file) && is_array($file)) {
      foreach ($file as $li) {
        include($li);
      }
    } elseif (is_null($file) && is_array($this->path)) {
      foreach ($this->path as $li) {
        include($li);
      }
    } else {
      $file = is_null($file) ? $this->path : $file;
      file_exists($file) ? include ($file) : die($file);
    }
  }
}