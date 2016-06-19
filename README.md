Terminal
========

[![Build Status on TravisCI](https://secure.travis-ci.org/xp-forge/terminal.svg)](http://travis-ci.org/xp-forge/terminal)
[![XP Framework Module](https://raw.githubusercontent.com/xp-framework/web/master/static/xp-framework-badge.png)](https://github.com/xp-framework/core)
[![BSD Licence](https://raw.githubusercontent.com/xp-framework/web/master/static/licence-bsd.png)](https://github.com/xp-framework/core/blob/master/LICENCE.md)
[![Required PHP 5.5+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-5_5plus.png)](http://php.net/)
[![Supports PHP 7.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-7_0plus.png)](http://php.net/)
[![Supports HHVM 3.4+](https://raw.githubusercontent.com/xp-framework/web/master/static/hhvm-3_4plus.png)](http://hhvm.com/)
[![Latest Stable Version](https://poser.pugx.org/xp-forge/terminal/version.png)](https://packagist.org/packages/xp-forge/terminal)

Terminal control.

Styles
------
The terminal library adds support for color to the `Console` class. There are five predefined styles:

```php
use util\cmd\Console;

Console::writeLine('This is <primary>primary</>!');
Console::writeLine('This is <success>success</>!');
Console::writeLine('This is <info>info</>!');
Console::writeLine('This is <warning>warning</>!');
Console::writeLine('This is <danger>danger</>!');
```

Colors
------
For direct color control, the colors can be chosen directly by supplying their names. Foreground and background colors are separated by the `@` sign:

```php
use util\cmd\Console;

Console::writeLine('<red>An error occured</>');
Console::writeLine('<white@green>OK: 100 Tests succeeded</>');
```

Attributes
----------
There are three more attributes. Not all terminals support these, though!

```php
use util\cmd\Console;

Console::writeLine('<underline>http://localhost</>');
Console::writeLine('<bold>Watch out!</>');
Console::writeLine('<italic>- The XP Framework group</>');
```