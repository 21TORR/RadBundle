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
