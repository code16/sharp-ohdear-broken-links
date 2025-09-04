## Sharp OhDear Broken Links
A Laravel package designed to be used with [Sharp](https://github.com/code16/sharp) to display [broken links from your OhDear monitoring](https://ohdear.app/features/broken-page-and-mixed-content-detection).

## Installation

```bash
composer require code16/sharp-ohdear-broken-links
```

## Usage
This package is designed to add a broken links' list in your Sharp back-office.
It uses the OhDear env keys to connect to OhDear API, you need at least `OH_DEAR_MONITOR_ID` and `OH_DEAR_API_TOKEN`.

Optionally, you can publish the package configuration with:
```bash
php artisan vendor:publish --tag=sharp-ohdear-broken-links-config
```

In your [Sharp Configuration Service Provider](https://sharp.code16.fr/docs/guide/#configuration-via-a-new-service-provider), add the broken links' entity :
```php
class SharpConfigServiceProvider extends SharpAppServiceProvider
{
    protected function configureSharp(SharpConfigBuilder $config): void
    {
        $config
            ->setName('Your beautiful project')
            ->setSharpMenu(AppSharpMenu::class)
            ->discoverEntities()
            ->declareEntity(Code16\SharpOhdearBrokenLinks\Sharp\Entities\BrokenLinkEntity::class); // <-- declare the package entity here
    }
```

Then add it to your sharp menu:
```php
class AppSharpMenu extends SharpMenu
{
    public function build(): self
    {
        // [...]
        return $this
            ->addEntityLink(Code16\SharpOhdearBrokenLinks\Sharp\Entities\BrokenLinkEntity::class, 'Broken links', 'fas-link-slash');
    }
}
```

## Credits

- [Arnaud Becher](https://github.com/smknstd)
- [Lucien PUGET](https://github.com/patrickepatate)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
