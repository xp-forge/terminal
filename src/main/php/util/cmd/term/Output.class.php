<?php namespace util\cmd\term;

use io\streams\OutputStream;
use io\streams\OutputStreamWriter;
use util\Objects;

class Output implements OutputStreamWriter {
  public static $direct;
  private $out= null;

  /**
   * Constructor
   *
   * @param  io.streams.OutputStream $out
   */
  public function __construct($out) {
    $this->out= $out;
  }
  
  /**
   * Return underlying output stream
   *
   * @return io.streams.OutputStream
   */
  public function getStream() {
    return $this->out;
  }

  /**
   * Return underlying output stream
   *
   * @param  io.streams.OutputStream $stream
   * @return void
   */
  public function setStream(OutputStream $stream) {
    $this->out= $stream;
  }

  /**
   * Flush output buffer
   *
   * @return void
   */
  public function flush() {
    $this->out->flush();
  }

  /**
   * Print arguments
   *
   * @param  var... $args
   * @return void
   */
  public function write(... $args) {
    $stack= [];
    foreach ($args as $arg) {
      if (is_string($arg)) {
        $this->out->write(Terminal::format($arg, $stack));
      } else {
        $this->out->write(Objects::stringOf($arg));
      }
    }

    while ($end= array_shift($stack)) {
      $this->out->write($end);
    }
  }

  /**
   * Print arguments and append a newline
   *
   * @param  var... $args
   * @return void
   */
  public function writeLine(... $args) {
    $this->write(...$args);
    $this->out->write("\n");
  }

  /**
   * Print a formatted string
   *
   * @param  string $format
   * @param  var... $args
   * @return void
   */
  public function writef($format, ... $args) {
    $stack= [];
    $formatted= Terminal::format($format, $stack);
    while ($end= array_shift($stack)) {
      $formatted.= $end;
    }

    $this->out->write(vsprintf($formatted, $args));
  }

  /**
   * Print a formatted string and append a newline
   *
   * @param  string $format
   * @param  var... $args
   * @return void
   */
  public function writeLinef($format, ... $args) {
    $this->writef($format, ...$args);
    $this->out->write("\n");
  }
}