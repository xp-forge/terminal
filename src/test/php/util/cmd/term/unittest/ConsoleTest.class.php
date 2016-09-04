<?php namespace util\cmd\term\unittest;

use util\cmd\Console;
use io\streams\MemoryOutputStream;
use util\Bytes;

class ConsoleTest extends \unittest\TestCase {

  /**
   * Assert formatted input matchs expected outcome
   *
   * @param  string $expected
   * @param  function(): void $block
   * @throws unittest.AssertionFailedError
   */
  private function assertWritten($expected, $block) {
    $out= Console::$out->getStream();
    $buffer= new MemoryOutputStream();
    Console::$out->setStream($buffer);

    try {
      $block();
    } finally {
      Console::$out->setStream($out);
    }

    $formatted= $buffer->getBytes();
    if ($expected !== $formatted) {
      $this->fail('equals', new Bytes($formatted), new Bytes($expected));
    }
  }

  #[@test]
  public function write() {
    $this->assertWritten("\e[31;1mTest\e[22;39m", function() {
      Console::write('<red>Test</>');
    });
  }

  #[@test]
  public function write_multiple_args() {
    $this->assertWritten("\e[31;1mTest\e[22;39m", function() {
      Console::write('<red>', 'Test', '</>');
    });
  }

  #[@test]
  public function writeLine() {
    $this->assertWritten("\e[31;1mTest\e[22;39m\n", function() {
      Console::writeLine('<red>Test</>');
    });
  }

  #[@test]
  public function writeLine_multiple_args() {
    $this->assertWritten("\e[31;1mTest\e[22;39m\n", function() {
      Console::writeLine('<red>', 'Test', '</>');
    });
  }
}
