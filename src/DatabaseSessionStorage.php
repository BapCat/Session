<?php namespace BapCat\Session;

use BapCat\Session\SessionGateway;

use DateInterval;
use DateTime;
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
    $dt = new DateTime();
    $dt->sub(new DateInterval("PT{$max_lifetime}S"));
    
    $this->gateway->query()->where('updated_at', '<=', $dt->format(DATE_ISO8601))->delete();
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
      $this->gateway->query()->insert($data);
    } else {
      $this->gateway->query()->where('session_token', $session_token)->update($data);
    }
    
    return true;
  }
}
