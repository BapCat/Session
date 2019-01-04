<?php declare(strict_types=1);

use BapCat\Hashing\Algorithms\Sha1WeakHasher;
use BapCat\Session\TransientSessionStorage;
use PHPUnit\Framework\TestCase;

class TransientSessionStorageTest extends TestCase {
  /** @var TransientSessionStorage $storage */
  private $storage;

  public function setUp(): void {
    parent::setUp();
    $this->storage = new TransientSessionStorage();
  }

  public function testOpen(): void {
    $this->assertTrue($this->storage->open('', ''));
  }

  public function testClose(): void {
    $this->assertTrue($this->storage->close());
  }

  public function testDestroy(): void {
    $this->storage->destroy('');
    $this->assertTrue(true);
  }

  public function testGc(): void {
    $this->storage->gc(0);
    $this->assertTrue(true);
  }

  public function testRead(): void {
    $this->assertSame('', $this->storage->read(''));
  }

  public function testWrite(): void {
    $this->assertTrue($this->storage->write('', ''));
  }
}
