<?php namespace util\cmd\term;

class Terminal {
  public static $theme= [
    'primary'   => ["\e[37;1m", "\e[0m"],
    'success'   => ["\e[32;1m", "\e[0m"],
    'info'      => ["\e[36;1m", "\e[0m"],
    'warning'   => ["\e[33;1m", "\e[0m"],
    'danger'    => ["\e[31;1m", "\e[0m"],

    'bold'      => ["\e[1m", "\e[22m"],
    'italic'    => ["\e[3m", "\e[23m"],
    'underline' => ["\e[4m", "\e[24m"]
  ];

  public static $colors= [
    'black'        => ['0', '0'],
    'dark-red'     => ['1', '0'],
    'dark-green'   => ['2', '0'],
    'dark-yellow'  => ['3', '0'],
    'dark-blue'    => ['4', '0'],
    'dark-magenta' => ['5', '0'],
    'dark-cyan'    => ['6', '0'],
    'gray'         => ['7', '0'],

    'dark-gray'    => ['0;1', '22;0'],
    'red'          => ['1;1', '22;0'],
    'green'        => ['2;1', '22;0'],
    'yellow'       => ['3;1', '22;0'],
    'blue'         => ['4;1', '22;0'],
    'magenta'      => ['5;1', '22;0'],
    'cyan'         => ['6;1', '22;0'],
    'white'        => ['7;1', '22;0']
  ];

  /**
   * Format a string containing `<styles>`...`</>` sequences.
   *
   * @param  string $in
   * @return string
   */
  public static function format($in) {
    preg_match_all('/<([a-z\/][a-z@,-]*)>/', $in, $matches, PREG_OFFSET_CAPTURE);

    $formatted= '';
    $offset= 0;
    foreach ($matches[1] as $match) {
      if ($match[1] > 1) {
        $formatted.= substr($in, $offset, $match[1] - $offset - 1);
      }

      if ('/' === $match[0]{0}) {
        $formatted.= array_pop($stack);
      } else {
        $set= $unset= '';
        foreach (explode(',', $match[0]) as $style) {
          if (isset(self::$theme[$style])) {
            $set.= self::$theme[$style][0];
            $unset= self::$theme[$style][1].$unset;
          } else {
            sscanf($style, '%[^@]@%s', $fg, $bg);
            $set.= "\e[3".self::$colors[$fg][0].($bg ? ';4'.self::$colors[$bg][0] : '').'m';
            $unset= "\e[".self::$colors[$fg][1].($bg ? ';'.self::$colors[$bg][1] : '').'m'.$unset;
          }
        }
        $formatted.= $set;
        $stack[]= $unset;
      }

      $offset= $match[1] + strlen($match[0]) + 1;
    }
    return $formatted.substr($in, $offset);
  }

  /**
   * Clears terminal
   *
   * @return void
   */
  public static function clear() {
    Output::$direct->write("\e[2J");
  }

  /**
   * Positions cursor
   *
   * @param  int $x
   * @param  int $y
   * @return void
   */
  public static function position($x, $y) {
    Output::$direct->write("\e[".$y.';'.$x.'H');
  }

  /**
   * Resets current line
   *
   * @return void
   */
  public static function reset() {
    Output::$direct->write("\r\e[K");
  }

  /**
   * Resets current line
   *
   * @param  int $x
   * @param  int $y
   * @param  string|string[] $arg Single or multiple lines
   * @return void
   */
  public static function write($x, $y, $arg) {
    foreach ((array)$arg as $n => $line) {
      Output::$direct->write("\e[".($y + $n).';'.$x.'H'.self::format($line));
    }
  }
}