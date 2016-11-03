<?php namespace BapCat\Session;

use BapCat\Session\SessionGateway;

use DateInterval;
use DateTime;
use SessionHandlerInterface;

class DatabaseSessionStorage implements SessionHandlerInterface {
  private $gateway;
  
  public function __construct(SessionGateway $gateway) {
    $this->gateway = $gateway;
  }
  
  public function open($save_path, $name) {
    return true;
  }
  
  public function close() {
    return true;
  }
  
  public function destroy($token) {
    return $this->gateway->query()->where('id', $token)->delete() !== 0;
  }
  
  public function gc($max_lifetime) {
    $dt = new DateTime();
    $dt->sub(new DateInterval("PT{$max_lifetime}S"));
    
    $this->gateway->query()->where('updated_at', '<=', $dt->format(DATE_ISO8601))->delete();
  }
  
  public function read($token) {
    $session = $this->gateway->query()->where('id', $token)->first();
    
    if($session === null) {
      return '';
    }
    
    return $session['data'];
  }
  
  public function write($token, $data) {
    $this->gateway->query()->replace([
      'id' => $token,
      'data' => $data,
    ]);
    
    return true;
  }
}
