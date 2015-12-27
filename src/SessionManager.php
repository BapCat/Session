<?php namespace BapCat\Session;

use BapCat\Collection\Collection;

use SessionHandlerInterface;

class SessionManager extends Collection {
  public function setHandler(SessionHandlerInterface $handler) {
    session_set_save_handler($handler);
  }
  
  public function open() {
    session_start();
    
    $this->collection = &$_SESSION;
  }
  
  public function close() {
    session_write_close();
  }
  
  public function regenerate() {
    session_regenerate_id(true);
  }
}
