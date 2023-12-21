3.0.0 (unreleased)
=====

* (improvement) Bump required Symfony.
* (bc) Remove sortable handlers.
* (bc) Remove simple entity search handler.
* (bc) Remove `Model`.
* (bc) Fix missing return types in `ModelInterface`.
* (internal) Use HtmlBuilder for `DataContainer`.
* (bc) Replace `IdTrait` + `TimestampsTrait` to `EntityFieldsTrait` and `ModifiableEntityFieldsTrait`.
* (bc) Moved `EntityInterface` to new namespace and add time created getter.


2.6.1
=====

* (bug) Remove invalid service binding.


2.6.0
=====

* (feature) Add `InMemoryCache`.


2.5.2
=====

* (bug) Do not modify original UnitOfWork in `DoctrineChangeChecker`.
* (improvement) Allow to automatically redact changed fields' values in `DoctrineChangeChecker::getEntityChanges()`.


2.5.1
=====

* (bug) Remove invalid check for class existence.


2.5.0
=====

* (feature) Add `DoctrineChangeChecker::getEntityChanges()`.


2.4.1
=====

* (deprecation) Mark custom `public` methods in `BaseController` as `@protected`. This is a deprecation notice for an upcoming BC break in v3.0.0.
* (improvement) Add `BaseController::getService()`, which will tell PhpStorm and PhpStan about the concrete service type.


2.4.0
=====

* (feature) Add and enable abilities voter, that votes on `CAN_` attributes.
* (internal) Require PHP 8.2.
* (feature) Add `DoctrineChangeChecker`, to check for content changes in the unit of work.


2.3.1
=====

* (improvement) Add explicit `ApiResponseNormalizer`.


2.3.0
=====

* (feature) Add `EntityModel`.
* (deprecation) Deprecate `Model`.
* (improvement) Require Symfony 6.2+.


2.2.0
=====

* (feature) Add `ApiResponse`.


2.1.0
=====

* (improvement) Require PHP 8.1+
* (improvement) Require Symfony 6.1+
* (deprecation) `Routable` was deprecated in favor of `Linkable`.
* (feature) Add `Linkable` and `LinkableInterface`.


2.0.1
=====

*   (improvement) Remove obsolete dependency on `21torr/cli`.


2.0.0
=====

*   (bc) Remove all command helpers. Use `21torr/cli` instead.
*   (improvement) Allow Symfony v6.
*   (bug) Fix usage of optional `translator` dependency


1.1.6
=====

*   (improvement) Add attribute support on entity traits


1.1.5
=====

*   (bug) Fix issue with `symfony/translator` being an optional dependency.


1.1.4
=====

*   (improvement) Allow newer versions of `psr/log`.
*   (improvement) Only allow PHP 8+


1.1.3
=====

*   (bug) Remove the `SerializedType`. Unused yet, and doesn't allow installation without doctrine.


1.1.2
=====

*   (deprecation) Deprecated the CLI helpers. Use `21torr/cli` instead.


1.1.1
=====

*   (improvement) Allow PHP 8.0
*   (improvement) Require Symfony 5.2
*   (bug) Run PHPUnit tests in the CI


1.1.0
=====

*   (improvement) Make nearly all dependencies optional.
*   (feature) Add `TorrCliStyle`.


1.0.0
=====

*   (feature) Add `SerializedType` doctrine type.
*   (feature) Add base entity helpers:
    *    `EntityInterface`, `SortableEntityInterface`
    *   `IdTrait`, `SortOrderTrait`, `TimestampsTrait`
*   (feature) Add `DataContainer`.
*   (feature) Add Twig function: `data_container`
*   (feature) Add Twig filter: `appendToArrayKey`
*   (feature) Add pagination related classes + helpers.
*   (feature) Add `FormErrorNormalizer`.
*   (feature) Add `BaseController`.
*   (feature) Add `Model` + `ModelInterface`.
*   (feature) Add `CommandHelper`.
*   (feature) Add `Routable`.
*   (feature) Add sortable related classes.
*   (feature) Add `StatsLog`.
*   (feature) Add `SimpleEntitySearchHandler`.
