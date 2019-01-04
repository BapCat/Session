<?php declare(strict_types=1); namespace BapCat\Session;

use SessionHandlerInterface;

class TransientSessionStorage implements SessionHandlerInterface {
  public function open($save_path, $name) {
    return true;
  }

  public function close() {
    return true;
  }

  public function destroy($session_id) {
    // no-op
  }

  public function gc($max_lifetime) {
    // no-op
  }

  public function read($session_id) {
    return '';
  }

  public function write($session_id, $session_data) {
    return true;
  }
}
