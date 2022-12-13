# @no-ci composer plugin

## install

``composer req andersundsehr/no-ci``

## what dose it do

This plugin adds the possibility to add `@no-ci`/`@ci` to any composer script.  
you can Add it in front of every possible composer script. [documentation](https://getcomposer.org/doc/articles/scripts.md#writing-custom-commands)

### Example composer.json
````json
{
  "scripts": {
    "test": [
      "@no-ci @php vendor/bin/phpunit -c phpunit.xml",
      "@ci @php vendor/bin/phpunit -c phpunit-ci.xml"
    ],
    "other:examples": [
      "@no-ci Composer\\Config::disableProcessTimeout",
      "@no-ci @clearCache",
      "@no-ci @composer install",
      "@no-ci @php script.php",
      "@no-ci @putenv COMPOSER=phpstan-composer.json",
      "@no-ci ls -alh"
    ],
    "clearCache": "rm -rf var"
  }
}
````
