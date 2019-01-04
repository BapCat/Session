<?php declare(strict_types=1); namespace BapCat\Session;

use BapCat\Phi\Ioc;
use BapCat\Services\ServiceProvider;

class TransientSessionServiceProvider implements ServiceProvider {
  /** @var Ioc $ioc */
  private $ioc;

  public function __construct(Ioc $ioc) {
    $this->ioc = $ioc;
  }

  public function register(): void {
    $this->ioc->singleton(SessionManager::class, function(): SessionManager {
      $manager = new SessionManager();
      $manager->setHandler($this->ioc->make(TransientSessionStorage::class));

      return $manager;
    });
  }

  public function boot(): void {
    $this->ioc->make(SessionManager::class)->open();
  }
}
