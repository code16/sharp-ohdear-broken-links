## Installation

```bash
composer require code16/sharp-ohdear-broken-links
```

## Usage

This package should only be added on a project that already have a ohdear monitoring setup. It will use the config value of `schedule-monitor.oh_dear.site_id`.

Then add in `config/sharp.php` :

```php
return [
    ...

    'entities' => [
        ...
        'brokenLinks' => Code16\SharpOhdearBrokenLinks\Sharp\Entities\BrokenLinkEntity::class,
    ],
```

and add in sharp menu file :

```php
public function build(): SharpMenu
{
    return $this
        ...
        ->addEntityLink('brokenLinks', 'Lien cassés (ohDear)', 'fa-chain-broken');
}
```

## Credits

- [Arnaud Becher](https://github.com/smknstd)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
