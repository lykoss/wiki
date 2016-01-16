This MediaWiki extension contains the functionality custom to https://werewolf.chat.

## Installation
To install, first clone the repository into your `extensions` directory, like so
```
git clone https://github.com/lykoss/wiki WerewolfWiki
```

Then, add the following to your `LocalSettings.php` file
```php
wfLoadExtension( 'WerewolfWiki' );
```

Verify the installation was successful by heading to Special:Version.
