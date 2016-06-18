<?php namespace util\cmd\term;

class Terminal {
  public static $theme= [
    '{primary}' => "\e[37;1m",
    '{success}' => "\e[32;1m",
    '{info}'    => "\e[36;1m",
    '{warning}' => "\e[33;1m",
    '{danger}'  => "\e[31;1m",
    '{/}'       => "\e[0m"
  ];
}