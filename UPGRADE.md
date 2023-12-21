2.x to 3.0
==========

* `Routable` was removed in favor of `Linkable` and `LinkableInterface`.
* The `BaseController` methods `::normalizeFormErrors()`, `::getLogger()` and `::fetchJsonRequestBody()` have been changed from `public` to `protected`. Please make sure that these methods are not being used externally within your controllers. 
* The sortable handlers were removed. There is no replacement.
* The simple entity search handlers were removed. There is no replacement.
* The `Model` class was removed. Use `EntityModel` instead.
* The missing return types in `ModelInterface` were added.


1.x to 2.0
==========

*   The CLI helpers in `src/Command` were removed. Use the corresponding classes from `21torr/cli` instead.
