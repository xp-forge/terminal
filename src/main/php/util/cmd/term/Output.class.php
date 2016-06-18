<?php namespace util\cmd\term;

class Output extends \io\streams\ConsoleOutputStream {

  /**
   * Write a string
   *
   * @param  string $arg
   * @return void
   */
  public function write($arg) { 
    parent::write(strtr($arg, Terminal::$theme));
  }
}