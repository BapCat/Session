<?php namespace BapCat\Session;

use BapCat\Hashing\Alogorithms\Sha256StrongHash;
use BapCat\Interfaces\Ioc\Ioc;
use BapCat\Remodel\EntityDefinition;
use BapCat\Remodel\Registry;
use BapCat\Services\ServiceProvider;

class DatabaseSessionServiceProvider implements ServiceProvider {
  private $ioc;
  private $remodel;
  
  public function __construct(Ioc $ioc, Registry $remodel) {
    $this->ioc     = $ioc;
    $this->remodel = $remodel;
  }
  
  public function register() {
    $def = new EntityDefinition(Session::class);
    $def->required('token', Sha256StrongHash::class);
    $def->required('data',  Text::class);
    $def->timestamps();
    
    $this->remodel->register($def);
    
    $this->ioc->singleton(SessionGateway::class, SessionGateway::class);
    $this->ioc->singleton(SessionRepository::class, SessionRepository::class);
    
    $manager = new SessionManager();
    $manager->setHandler($this->ioc->make(DatabaseSessionStorage::class));
    
    $this->ioc->bind(SessionManager::class, $manager);
  }
  
  public function boot() {
    
  }
}
