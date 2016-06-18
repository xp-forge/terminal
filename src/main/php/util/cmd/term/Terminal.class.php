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

  public static $fg= [
    'black'        => ["30", "0"],
    'dark-red'     => ["31", "0"],
    'dark-green'   => ["32", "0"],
    'dark-yellow'  => ["33", "0"],
    'dark-blue'    => ["34", "0"],
    'dark-magenta' => ["35", "0"],
    'dark-cyan'    => ["36", "0"],
    'gray'         => ["37", "0"],

    'dark-gray'    => ["30;1", "22;0"],
    'red'          => ["31;1", "22;0"],
    'green'        => ["32;1", "22;0"],
    'yellow'       => ["33;1", "22;0"],
    'blue'         => ["34;1", "22;0"],
    'magenta'      => ["35;1", "22;0"],
    'cyan'         => ["36;1", "22;0"],
    'white'        => ["37;1", "22;0"]
  ];

  public static $bg= [
    'black'        => ["40", "0"],
    'dark-red'     => ["41", "0"],
    'dark-green'   => ["42", "0"],
    'dark-yellow'  => ["43", "0"],
    'dark-blue'    => ["44", "0"],
    'dark-magenta' => ["45", "0"],
    'dark-cyan'    => ["46", "0"],
    'gray'         => ["47", "0"],

    'dark-gray'    => ["1;40", "22;0"],
    'red'          => ["1;41", "22;0"],
    'green'        => ["1;42", "22;0"],
    'yellow'       => ["1;43", "22;0"],
    'blue'         => ["1;44", "22;0"],
    'magenta'      => ["1;45", "22;0"],
    'cyan'         => ["1;46", "22;0"],
    'white'        => ["1;47", "22;0"]
  ];

  public static function format($arg) {
    preg_match_all('/<(\/|[a-z][a-z@,-]+)>/', $arg, $matches, PREG_OFFSET_CAPTURE);

    $formatted= '';
    $offset= 0;
    foreach ($matches[1] as $match) {
      if ($match[1] > 1) {
        $formatted.= substr($arg, $offset, $match[1] - $offset - 1);
      }

      if ('/' === $match[0]) {
        $formatted.= array_pop($stack);
      } else {
        $set= $unset= '';
        foreach (explode(',', $match[0]) as $style) {
          if (isset(self::$theme[$style])) {
            $set.= self::$theme[$style][0];
            $unset= self::$theme[$style][1].$unset;
          } else {
            sscanf($style, '%[^@]@%s', $fg, $bg);
            $set.= "\e[".self::$fg[$fg][0].($bg ? ';'.self::$bg[$bg][0] : '').'m';
            $unset= "\e[".self::$fg[$fg][1].($bg ? ';'.self::$bg[$bg][1] : '').'m'.$unset;
          }
        }
        $formatted.= $set;
        $stack[]= $unset;
      }

      $offset= $match[1] + strlen($match[0]) + 1;
    }
    return $formatted.substr($arg, $offset);
    // return str_replace("\e", "\\e", $formatted.substr($arg, $offset));
  }
}