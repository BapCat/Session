<?php namespace BapCat\Session;

use BapCat\Interfaces\Ioc\Ioc;
use BapCat\Services\ServiceProvider;

class TransientSessionServiceProvider implements ServiceProvider {
  private $ioc;
  
  public function __construct(Ioc $ioc) {
    $this->ioc = $ioc;
  }
  
  public function register() {
    $this->ioc->singleton(SessionManager::class, function() {
      $manager = new SessionManager();
      $manager->setHandler($this->ioc->make(TransientSessionStorage::class));
      
      return $manager;
    });
  }
  
  public function boot() {
    $this->ioc->make(SessionManager::class)->open();
  }
}
