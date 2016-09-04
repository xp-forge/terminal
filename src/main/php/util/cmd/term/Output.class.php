<?php namespace util\cmd\term;

use io\streams\OutputStream;

class Output implements \io\streams\OutputStreamWriter {
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
    $r= '';
    foreach ($args as $arg) {
      if (is_string($arg)) {
        $r.= $arg;
      } else {
        $r.= \xp::stringOf($arg);
      }
    }
    $this->out->write(Terminal::format($r));
  }

  /**
   * Print arguments and append a newline
   *
   * @param  var... $args
   * @return void
   */
  public function writeLine(... $args) {
    $r= '';
    foreach ($args as $arg) {
      if (is_string($arg)) {
        $r.= $arg;
      } else {
        $r.= \xp::stringOf($arg);
      }
    }
    $this->out->write(Terminal::format($r."\n"));
  }

  /**
   * Print a formatted string
   *
   * @param  string $format
   * @param  var... $args
   * @return void
   */
  public function writef($format, ... $args) {
    $this->out->write(Terminal::format(vsprintf($format, $args)));
  }

  /**
   * Print a formatted string and append a newline
   *
   * @param  string $format
   * @param  var... $args
   * @return void
   */
  public function writeLinef($format, ... $args) {
    $this->out->write(Terminal::format(vsprintf($format, $args)."\n"));
  }
}