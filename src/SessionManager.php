<?php declare(strict_types=1); namespace BapCat\Session;

use BapCat\Collection\Collection;

use SessionHandlerInterface;

class SessionManager extends Collection {
  public function setHandler(SessionHandlerInterface $handler): void {
    session_set_save_handler($handler);
  }

  public function open(): bool {
    if(!@session_start()) {
      session_id(hash('sha256', random_bytes(64)));

      if(!session_start()) {
        return false;
      }

      if(!$this->regenerate()) {
        return false;
      }
    }

    $this->collection = &$_SESSION;

    return true;
  }

  public function close(): void {
    session_write_close();
  }

  public function regenerate(): bool {
    return session_regenerate_id(true);
  }

  public function destroy(): bool {
    return session_destroy();
  }

  public function reset(): void {
    session_reset();
  }

  public function abort(): void {
    session_abort();
  }

  public function clear() {
    session_unset();
  }
}
