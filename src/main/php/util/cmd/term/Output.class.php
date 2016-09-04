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

  public function format($in, &$stack) {
    $offset= 0;
    $length= strlen($in);
    $formatted= '';

    do {
      $p= strcspn($in, '<', $offset);
      $formatted.= substr($in, $offset, $p);
      $offset+= $p + 1;
      if ($offset >= $length) break;

      $e= strcspn($in, '>', $offset);
      $token= substr($in, $offset, $e);
      if ('' === $token) {
        $e= strpos($in, '</>', $offset) - $offset;
        $formatted.= substr($in, $offset + 1, $e - 1);
        $e+= 2;
      } else if ('/' === $token{0}) {
        $formatted.= array_pop($stack);
      } else if (strlen($token) !== strspn($token, 'abcdefghijklmnopqrstuvwxyz0123456789-,@')) {
        $formatted.= substr($in, $offset - 1, $e + 1 + 1);
      } else {
        list($set, $unset)= Terminal::transition($token);
        $formatted.= $set;
        $stack[]= $unset;
      }

      $offset+= $e + 1;
    } while ($offset < $length);

    return $formatted;
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
        $this->out->write($this->format($arg, $stack));
      } else {
        $this->out->write(\xp::stringOf($arg));
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
    $formatted= $this->format($format, $stack);
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