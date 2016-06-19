<?php namespace util\cmd\term;

class Output extends \io\streams\ConsoleOutputStream {
  public static $direct;

  /**
   * Write a string
   *
   * @param  string $arg
   * @return void
   */
  public function write($arg) {
    parent::write(Terminal::format($arg));
  }
}