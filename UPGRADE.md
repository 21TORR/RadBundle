2.x to 3.0
==========

* `Routable` was removed in favor of `Linkable` and `LinkableInterface`.
* The `BaseController` methods `::normalizeFormErrors()`, `::getLogger()` and `::fetchJsonRequestBody()` have been changed from `public` to `protected`. Please make sure that these methods are not being used externally within your controllers. 


1.x to 2.0
==========

*   The CLI helpers in `src/Command` were removed. Use the corresponding classes from `21torr/cli` instead.
