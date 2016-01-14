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
  
  public function destroy($session_token) {
    $this->gateway->query()->where('session_token', $session_token)->delete();
  }
  
  public function gc($max_lifetime) {
    $this->gateway->query()->where('updated_at', '<=', time() - $max_lifetime)->delete();
  }
  
  public function read($session_token) {
    $session = $this->gateway->query()->where('session_token', $session_token)->first();
    
    if($session === null) {
      return '';
    }
    
    $this->exists = true;
    
    return $session['session_data'];
  }
  
  public function write($session_token, $session_data) {
    $data = [
      'session_token' => $session_token,
      'session_data'  => $session_data
    ];
    
    if(!$this->exists) {
    } else {
      $this->gateway->query()->where('session_token', $session_token)->update($data);
    }
    
    return true;
  }
}
