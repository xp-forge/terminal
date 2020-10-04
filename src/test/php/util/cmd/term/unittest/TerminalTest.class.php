<?php namespace util\cmd\term\unittest;

use unittest\{Test, Values};
use util\Bytes;
use util\cmd\term\Terminal;

class TerminalTest extends \unittest\TestCase {

  /**
   * Assert formatted input matchs expected outcome
   *
   * @param  string $expected
   * @param  string $input
   * @throws unittest.AssertionFailedError
   */
  private function assertFormatted($expected, $input) {
    $stack= [];
    $formatted= Terminal::format($input, $stack);
    if ($expected !== $formatted) {
      $this->fail('equals', new Bytes($formatted), new Bytes($expected));
    }
  }

  #[Test]
  public function format_empty() {
    $this->assertFormatted('', '');
  }

  #[Test]
  public function format_non_empty() {
    $this->assertFormatted('Test', 'Test');
  }

  #[Test]
  public function format_with_color() {
    $this->assertFormatted("\e[31;1mTest\e[22;39m", '<red>Test</>');
  }

  #[Test]
  public function end_tags_may_be_spelled_out() {
    $this->assertFormatted("\e[31;1mTest\e[22;39m", '<red>Test</red>');
  }

  #[Test]
  public function format_with_color_and_underline() {
    $this->assertFormatted("\e[31m\e[4mTest\e[24m\e[39m", '<dark-red,underline>Test</>');
  }

  #[Test]
  public function format_with_color_and_nested_attribute() {
    $this->assertFormatted("\e[31mTest \e[4mLink\e[24m\e[39m", '<dark-red>Test <underline>Link</></>');
  }

  #[Test, Values(['>>> Sent', '<<< Recieved'])]
  public function issue_2($input) {
    $this->assertFormatted($input, $input);
  }

  #[Test]
  public function raw_xml() {
    $this->assertFormatted('<?xml version="1.0" encoding="utf-8"?>', '<><?xml version="1.0" encoding="utf-8"?></>');
  }

  #[Test]
  public function raw_stacktrace() {
    $this->assertFormatted(
      'at <main>::array_pop() [line 76 of Terminal.class.php] Test',
      '<>at <main>::array_pop() [line 76 of Terminal.class.php]</> Test'
    );
  }
}