<?php

namespace Code16\SharpOhdearBrokenLinks;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SharpOhdearBrokenLinksServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('sharp-ohdear-broken-links');
    }
}
