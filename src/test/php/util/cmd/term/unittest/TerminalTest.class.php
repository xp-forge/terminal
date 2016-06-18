<?php namespace util\cmd\term\unittest;

use util\cmd\term\Terminal;
use util\Bytes;

class TerminalTest extends \unittest\TestCase {

  private function assertFormatted($expected, $input) {
    $formatted= Terminal::format($input);
    if ($expected !== $formatted) {
      $this->fail('equals', new Bytes($formatted), new Bytes($expected));
    }
  }

  #[@test]
  public function format_empty() {
    $this->assertFormatted('', '');
  }

  #[@test]
  public function format_non_empty() {
    $this->assertFormatted('Test', 'Test');
  }

  #[@test]
  public function format_with_color() {
    $this->assertFormatted("\e[31;1mTest\e[22;0m", '<red>Test</>');
  }

  #[@test]
  public function format_with_color_and_underline() {
    $this->assertFormatted("\e[31m\e[4mTest\e[24m\e[0m", '<dark-red,underline>Test</>');
  }

  #[@test]
  public function format_with_color_and_nested_attribute() {
    $this->assertFormatted("\e[31mTest \e[4mLink\e[24m\e[0m", '<dark-red>Test <underline>Link</></>');
  }
}