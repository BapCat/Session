<?php namespace BapCat\Session;

use BapCat\Session\SessionGateway;

use SessionHandlerInterface;

class DatabaseSessionStorage implements SessionHandlerInterface {
  private $gateway;
  private $session;
  
  private $exists;
  
  public function __construct(SessionGateway $gateway) {
    $this->gateway = $gateway;
  }
  
  public function open($save_path, $name) {
    return true;
  }
  
  public function close() {
    return true;
  }
  
  public function destroy($session_id) {
    $this->gateway->query()->where('session_id', $session_id)->delete();
  }
  
  public function gc($max_lifetime) {
    $this->gateway->query()->where('updated_at', '<=', time() - $max_lifetime)->delete();
  }
  
  public function read($session_id) {
    $session = $this->gateway->query()->where('session_id', $session_id)->first();
    
    if($session === null) {
      return '';
    }
    
    $this->exists = true;
    
    return $session['session_data'];
  }
  
  public function write($session_id, $session_data) {
    $data = [
      'session_id'   => $session_id,
      'session_data' => $session_data
    ];
    
    if(!$this->exists) {
      $this->gateway->query()->insert($data);
    } else {
      $this->gateway->query()->where('session_id', $session_id)->update($data);
    }
    
    return true;
  }
}
