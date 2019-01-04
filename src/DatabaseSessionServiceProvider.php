<?php declare(strict_types=1); namespace BapCat\Session;

use BapCat\Hashing\Algorithms\Sha256StrongHash;
use BapCat\Phi\Ioc;
use BapCat\Remodel\EntityDefinition;
use BapCat\Remodel\Registry;
use BapCat\Services\ServiceProvider;

class DatabaseSessionServiceProvider implements ServiceProvider {
  /** @var Ioc $ioc */
  private $ioc;

  /** @var Registry $remodel */
  private $remodel;

  public function __construct(Ioc $ioc, Registry $remodel) {
    $this->ioc     = $ioc;
    $this->remodel = $remodel;
  }

  public function register(): void {
    $def = new EntityDefinition(Session::class);
    $def->id(Sha256StrongHash::class)->mapsTo('token');
    $def->required('data', Text::class);
    $def->timestamps();

    $this->remodel->register($def);

    $this->ioc->singleton(SessionGateway::class, SessionGateway::class);
    $this->ioc->singleton(SessionRepository::class, SessionRepository::class);

    $this->ioc->singleton(SessionManager::class, function() {
      $manager = new SessionManager();
      $manager->setHandler($this->ioc->make(DatabaseSessionStorage::class));

      return $manager;
    });
  }

  public function boot(): void {
    $this->ioc->make(SessionManager::class)->open();
  }
}
