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

Each docblock comment can have tags which start with `@` sign and each tag can have an array of properties and an optional description. The next list shows which tags are currently supported:

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

So for example if tag "@return string A string" is parsed, it's properties will only contain `type` entry:

```php
$returnTag = (new Stein197\Doxer\Doc('/** @return string A string */'))->getTags()[0];
$returnTag->getDescription(); // "A string"
$returnTag->getProperties(); // ['type' => 'string']
```

But if the tag does not match any of predefined, then it will be parsed as `@tag [<description>]`. So for example if tag "@nonexistent string A string" is parsed, all it will contain is a description:

```php
$tag = (new Stein197\Doxer\Doc('/** @nonexistent string A string */'))->getTags()[0];
$tag->getDescription(); // "string A string"
$tag->getProperties(); // null
```

> NOTE! If tag accepts name property which will start with `$` sign (`@param`, `@var` tags), then after retrieving `name` property the name will be retrieved without leading "$":
```php
$doc = new Stein197\Doxer\Doc('/** @param Type $arg1 */');
$doc->getTags()[0]->getProperties(); // ['name' => 'arg1']
```

## API
The only class that is supposed to be used is `Stein197\Doxer\Doc`, which accepts a docblock string as the only constructor parameter. Then the next methods could be used:

|Method|Return type|Description|
|---|---|---|
|`getDescription()`|`string`|Returns docblock description or `null` if it's not present|
|`getTags()`|`Stein197\Doxer\Tag[]`|Returns an array of tags or null if there are no tags present|

The next table contains list of `Stein197\Doxer\Tag[]` public methods:

|Method|Return type|Description|
|---|---|---|
|`getDescription()`|`string`|Returns tag description or `null` if it's not present|
|`getName()`|`string`|Returns the name of a tag|
|`getProperties()`|`string[]`|Returns an array of tag properties|

## Tests
To run unit tests call the `test` composer script:
```
composer run test
```
