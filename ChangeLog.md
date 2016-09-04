Terminal change log
===================

## ?.?.? / ????-??-??

## 0.5.0 / 2016-09-05

* Changed `Console::writef()` and `writeLinef()` to only tokenize
  format string and not arguments
  (@thekid)
* Changed non-string arguments to `Console::write()` and `writeLine()`
  to no longer be tokenized
  (@thekid)

## 0.4.0 / 2016-09-04

* **Heads up: Dropped PHP 5.5 support - now requires PHP 5.6 minimum!**
  (@thekid)
* Fixed writing multiple arguments with Console. See PR #3 - @thekid
* Added compatibility with XP 8 - @thekid

## 0.3.0 / 2016-07-05

* Fixed issue #2: Terminal confused by debug output - @thekid

## 0.2.0 / 2016-06-20

* Changed preference to bundled `tput` as using Cygwin variant breaks
  ANSI escape sequences inside a Windows shell
  (@thekid)
* Added method to return terminal size as [colums, lines] tuple
  (@thekid)
* Added methods to clear terminal, position cursor and write text at
  given coordinates
  (@thekid)
* Allowed end tags to be spelled-out. The suggested usage is to write
  `<tag>`...`</>`, but `<tag>`...`</tag>` is fine now, too. This can
  be benefitial when editors have in-string autocompletion. Correct
  nesting is *not* verified!
  (@thekid)

## 0.1.0 / 2016-06-19

* Hello World! First release - @thekid