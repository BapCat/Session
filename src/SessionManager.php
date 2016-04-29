<?php namespace BapCat\Session;

use BapCat\Collection\Collection;

use SessionHandlerInterface;

class SessionManager extends Collection {
  public function setHandler(SessionHandlerInterface $handler) {
    session_set_save_handler($handler);
  }
  
  public function open() {
    $success = session_start();
    
    $this->collection = &$_SESSION;
    
    return $success;
  }
  
  public function close() {
    session_write_close();
  }
  
  public function regenerate() {
    return session_regenerate_id(true);
  }
  
  public function destroy() {
    return session_destroy();
  }
  
  public function reset() {
    session_reset();
  }
  
  public function abort() {
    session_abort();
  }
  
  public function clear() {
    session_unset();
  }
}
