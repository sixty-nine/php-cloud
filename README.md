# PhpCloud

*This is experimental work in progress*.

## Install

```
composer install
bower install
```

## Fonts

This repository does not contain TTF fonts. You will have to use your own.

 * Place them in somewhere in the project tree (a good place would be `src/SixtyNine/CloudBundle/Resources/fonts`
 * Reference them in the `SixtyNine\CloudBundle\Manager\FontsManager`
   * Add them in the `$knownFonts` array
   * Update the path to the fonts in the constructor

## Initialize the app

```bash
# Create the DB
app/console doctrine:schema:create

# Load default palettes
app/console sn:palettes:load-default

# Create a user
app/console fos:user:create admin admin@localhost 123
app/console fos:user:activate admin
app/console fos:user:promote admin ROLE_ADMIN

# Cleanup
app/console cache:clear
```

## Example

![Cloud example](https://github.com/sixty-nine/php-cloud/blob/master/doc/cloud.png)

## License

This source file is subject to the MIT license that is bundled  with this source code in the file `doc\LICENCE`.
