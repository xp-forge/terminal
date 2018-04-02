<?php namespace util\cmd\term\unittest;

use util\cmd\Console;
use io\streams\MemoryOutputStream;
use util\Bytes;
use lang\Value;

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

  /**
   * Returns an object with a toString() output equaling the given value
   *
   * @param  string $value
   * @return lang.Value
   */
  private function value($value) {
    return newinstance(Value::class, [], [
      'toString'  => function() use($value) { return $value; },
      'hashCode'  => function() { return spl_object_hash($this); },
      'compareTo' => function($value) { return $value === $this ? 0 : 1; }
    ]);
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

  #[@test]
  public function write_object() {
    $this->assertWritten("\e[31;1mTest\e[22;39m", function() {
      Console::write('<red>', $this->value('Test'), '</>');
    });
  }

  #[@test]
  public function write_object_with_end_tag() {
    $this->assertWritten("\e[31;1m</>\e[22;39m", function() {
      Console::write('<red>', $this->value('</>'), '</>');
    });
  }

  #[@test]
  public function writeLine_object() {
    $this->assertWritten("\e[31;1mTest\e[22;39m\n", function() {
      Console::writeLine('<red>', $this->value('Test'), '</>');
    });
  }

  #[@test]
  public function writeLine_object_with_end_tag() {
    $this->assertWritten("\e[31;1m</>\e[22;39m\n", function() {
      Console::writeLine('<red>', $this->value('</>'), '</>');
    });
  }

  #[@test]
  public function writef() {
    $this->assertWritten("\e[31;1mTest\e[22;39m", function() {
      Console::writef('<red>%s</>', 'Test');
    });
  }

  #[@test]
  public function writeLinef() {
    $this->assertWritten("\e[31;1mTest\e[22;39m\n", function() {
      Console::writeLinef('<red>%s</>', 'Test');
    });
  }

  #[@test]
  public function writef_argument_with_end_tag() {
    $this->assertWritten("\e[31;1m</>\e[22;39m", function() {
      Console::writef('<red>%s</>', '</>');
    });
  }

  #[@test]
  public function writeLinef_argument_with_end_tag() {
    $this->assertWritten("\e[31;1m</>\e[22;39m\n", function() {
      Console::writeLinef('<red>%s</>', '</>');
    });
  }
}
