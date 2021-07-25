# PHP docblock comment parser
Simple PHP documentation comment parser. First, install it via composer as follows:
```
composer require stein197/doxer
```
And then use it like this:
```php
$docblock = <<<DOC
/**
 * Description.
 * Another line
 * of description.
 * @param int \$age Age parameter.
 * @param string \$name Name
 *                      parameter.
 * @return Person The person.
 * @throws Exception In case of errors.
 * 
DOC;
$doc = new Stein197\Doxer\Doc($docblock);
echo $doc->getDescription(); // "Description. Another line of description."
$tags = $doc->getTags(); // Returns and array of Stein197\Doxer\Tag instances
foreach ($tags as $tag) {
	echo $tag->getDescription(); // Handles multiple lines of description
	echo $tag->getName(); // Returns the name of the tag without "@" char
	foreach ($tag->getProperties() as $name => $value) {
		echo "$name=$value"; // Tag's properties such as "version", "type", etc.
	}
}
```
Each docblock comment can have tags which start with `@` sign and each tag can have an array of properties and an optional description. The next list shows which tags are parsed in a "special" way, i.e. tags that can have properties:
- @author `<name>` `[<email>]`
- @deprecated `[<version>]` `[<description>]`
- @license `[<url>]` `[<description>]`
- @link `<uri>` `[<description>]`
- @param `<type>` `<name>` `[<description>]`
- @return `<type>` `[<description>]`
- @see `<uri>` `[<description>]`
- @since `<version>` `[<description>]`
- @var `<type>` `[<name>]` `[<description>]`
- @uses `<uri>` `[<description>]`
- @throws `<type>` `[<description>]`

If tag does not match any of these, the it will parsed as `@tag [<description>]`. So for example if tag "@return string A string" is parsed, it's properties will be only `type`:
```php
(new Stein197\Doxer\Doc('/** @return string A string */'))->getTags()[0]->getProperties(); // Return ['type' => 'string']
```

> NOTE! If tag accepts name parameter which will start with `@` sign (@param for example), then after retrieving `name` property the name will be retrieved without leading "$":
```php
echo (new Stein197\Doxer\Doc('/** @param Type $name */'))->getTags()[0]->getProperties()['name']; // "name"
```

## Tests
To run unit tests call the `test` composer script:
```
composer run test
```
