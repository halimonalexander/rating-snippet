# Rating snippet
A simple snippet that can allow drawing rating using stars in few seconds.
As default, uses http://schema.org markup.

Example:
```php
use HalimonAlexander\HtmlSnippets\Rating;

$rating = new Rating(true, true, 10);
echo $rating->fetch(7.8, 124);
```