<?php namespace util\cmd\term;

class Terminal {
  public static $theme= [
    'primary'   => ["\e[37;1m", "\e[22;39m"],
    'success'   => ["\e[32;1m", "\e[22;39m"],
    'info'      => ["\e[36;1m", "\e[22;39m"],
    'warning'   => ["\e[33;1m", "\e[22;39m"],
    'danger'    => ["\e[31;1m", "\e[22;39m"],

    'reset'     => ["\e[0m", ""],
    'bold'      => ["\e[1m", "\e[22m"],
    'italic'    => ["\e[3m", "\e[23m"],
    'underline' => ["\e[4m", "\e[24m"],
  ];

  public static $colors= [
    'black'        => ['%0', '%9'],
    'dark-red'     => ['%1', '%9'],
    'dark-green'   => ['%2', '%9'],
    'dark-yellow'  => ['%3', '%9'],
    'dark-blue'    => ['%4', '%9'],
    'dark-magenta' => ['%5', '%9'],
    'dark-cyan'    => ['%6', '%9'],
    'gray'         => ['%7', '%9'],

    'dark-gray'    => ['%0;1', '22;%9'],
    'red'          => ['%1;1', '22;%9'],
    'green'        => ['%2;1', '22;%9'],
    'yellow'       => ['%3;1', '22;%9'],
    'blue'         => ['%4;1', '22;%9'],
    'magenta'      => ['%5;1', '22;%9'],
    'cyan'         => ['%6;1', '22;%9'],
    'white'        => ['%7;1', '22;%9']
  ];

  private static function transition($styles) {
    $set= $unset= '';
    foreach (explode(',', $styles) as $style) {
      if (isset(self::$theme[$style])) {
        $set.= self::$theme[$style][0];
        $unset= self::$theme[$style][1].$unset;
      } else {
        sscanf($style, '%[^@]@%s', $fg, $bg);
        $set.= "\e[".strtr(self::$colors[$fg][0], '%', '3').($bg ? ';'.strtr(self::$colors[$bg][0], '%', '4') : '').'m';
        $unset= "\e[".strtr(self::$colors[$fg][1], '%', '3').($bg ? ';'.strtr(self::$colors[$bg][1], '%', '4') : '').'m'.$unset;
      }
    }
    return [$set, $unset];
  }

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
        list($set, $unset)= self::transition($match[0]);
        $formatted.= $set;
        $stack[]= $unset;
      }

      $offset= $match[1] + strlen($match[0]) + 1;
    }
    return $formatted.substr($in, $offset);
  }

  private static function resolve($command, $path) {
    $extensions= [''] + explode(PATH_SEPARATOR, getenv('PATHEXT'));
    $paths= explode(PATH_SEPARATOR, $path);
    foreach ($paths as $path) {
      if ('' === $path) continue;
      foreach ($extensions as $ext) {
        $q= $path.DIRECTORY_SEPARATOR.$command.$ext;
        if (is_file($q)) return realpath($q);
      }
    }
    return $command;
  }

  /**
   * Returns size
   *
   * @return  int[] columns, rows
   */
  public static function size() {
    static $options= ['bypass_shell' => true, 'suppress_errors' => true];

    if (false !== ($col= getenv('COLUMNS'))) {
      return [(int)$col, (int)getenv('LINES')];
    } else {
      $command= self::resolve('tput', dirname(getenv('XP_EXE')).PATH_SEPARATOR.getenv('PATH'));
      $p= proc_open($command.' -S', [['pipe', 'r'], ['pipe', 'w']], $pipes, null, null, $options);
      fputs($pipes[0], "lines\ncols\n");
      fclose($pipes[0]);
      $lines= fgets($pipes[1]);
      $columns= fgets($pipes[1]);
      fclose($pipes[1]);
      if (0 === proc_close($p)) {
        return [(int)$columns, (int)$lines];
      }
    }
    return [80, 24];
  }

  /**
   * Applies style for a given block
   *
   * @param  string $styles
   * @param  function(): void $block
   * @return void
   */
  public static function styled($styles, $block) {
    list($set, $unset)= self::transition($styles);
    Output::$direct->write($set);
    try {
      $block();
    } finally {
      Output::$direct->write($unset);
    }
  }

  /**
   * Clears terminal
   *
   * @param  string $styles Optional
   * @return void
   */
  public static function clear($styles= null) {
    $styles && Output::$direct->write(self::transition($styles)[0]);
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
    Output::$direct->write("\e[".(int)$y.';'.(int)$x.'H');
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
   * Writes text at a given position
   *
   * @param  int $x
   * @param  int $y
   * @param  string|string[] $arg Single or multiple lines
   * @return void
   */
  public static function write($x, $y, $arg) {
    foreach ((array)$arg as $n => $line) {
      Output::$direct->write("\e[".(int)($y + $n).';'.(int)$x.'H'.self::format($line));
    }
  }
}