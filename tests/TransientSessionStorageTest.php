<?php

use BapCat\Hashing\Algorithms\Sha1WeakHasher;
use BapCat\Session\TransientSessionStorage;

class TransientSessionStorageTest extends PHPUnit_Framework_TestCase {
  public function setUp() {
    $this->storage = new TransientSessionStorage();
  }
  
  public function testOpen() {
    $this->assertTrue($this->storage->open('', ''));
  }
  
  public function testClose() {
    $this->assertTrue($this->storage->close());
  }
  
  public function testDestroy() {
    $this->storage->destroy('');
  }
  
  public function testGc() {
    $this->storage->gc(0);
  }
  
  public function testRead() {
    $this->assertSame('', $this->storage->read(''));
  }
  
  public function testWrite() {
    $this->assertTrue($this->storage->write('', ''));
  }
}
