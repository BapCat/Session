<?php namespace BapCat\Session;

use BapCat\Session\SessionGateway;

use DateInterval;
use DateTime;
use SessionHandlerInterface;

class DatabaseSessionStorage implements SessionHandlerInterface {
  private $gateway;
  private $session;
  
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
    return $this->gateway->query()->where('session_token', $session_token)->delete() !== 0;
  }
  
  public function gc($max_lifetime) {
    $dt = new DateTime();
    $dt->sub(new DateInterval("PT{$max_lifetime}S"));
    
    $this->gateway->query()->where('updated_at', '<=', $dt->format(DATE_ISO8601))->delete();
  }
  
  public function read($session_token) {
    $this->session = $this->gateway->query()->where('session_token', $session_token)->first();
    
    if($this->session === null) {
      $this->session = [];
      return '';
    }
    
    return $this->session['session_data'];
  }
  
  public function write($session_token, $session_data) {
    $this->session['session_token'] = $session_token;
    $this->session['session_data']   = $session_data;
    
    $this->gateway->query()->replace($this->session);
    
    return true;
  }
}
