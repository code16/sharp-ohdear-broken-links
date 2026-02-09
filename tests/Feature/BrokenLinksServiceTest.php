<?php

use Code16\SharpOhdearBrokenLinks\Services\BrokenLinksService;
use Code16\SharpOhdearBrokenLinks\Exceptions\MonitorIdIsNotDefinedException;
use OhDear\PhpSdk\Dto\BrokenLink;
use OhDear\PhpSdk\OhDear;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    config()->set('broken-links.api_token', 'fake-token');
    config()->set('broken-links.monitor_id', 123);
    config()->set('cache.default', 'array');
    config()->set('broken-links.cache.count', 60);
});

it('can get broken links from OhDear SDK', function () {
    $mock = Mockery::mock(OhDear::class);
    $mock->shouldReceive('brokenLinks')
        ->with(123)
        ->once()
        ->andReturn([
            new BrokenLink(404, 'https://example.com/broken', '/broken', 'https://example.com/page', '/page', 'Broken link', true),
        ]);

    app()->bind(OhDear::class, function ($app, $parameters) use ($mock) {
        return $mock;
    });

    $service = new BrokenLinksService();
    $links = $service->getBrokenLinks();

    expect($links)->toHaveCount(1)
        ->and($links[0]->statusCode)->toBe(404);
});

it('throws an exception if monitor_id is not defined', function () {
    config()->set('broken-links.monitor_id', null);

    $mock = Mockery::mock(OhDear::class);
    app()->bind(OhDear::class, fn() => $mock);

    $service = new BrokenLinksService();
    $service->getBrokenLinks();
})->throws(MonitorIdIsNotDefinedException::class);

it('caches the broken links count', function () {
    $mock = Mockery::mock(OhDear::class);
    $mock->shouldReceive('brokenLinks')
        ->with(123)
        ->once() // Should only be called once due to caching
        ->andReturn([
            new BrokenLink(404, 'https://example.com/broken', '/broken', 'https://example.com/page', '/page', 'Broken link', true),
            new BrokenLink(404, 'https://example.com/broken2', '/broken2', 'https://example.com/page', '/page', 'Broken link 2', true),
        ]);

    app()->bind(OhDear::class, fn() => $mock);

    $service = new BrokenLinksService();

    // First call, should call the SDK
    expect($service->getBrokenLinksCount())->toBe(2);
    expect(Cache::store('array')->has('broken-links.count'))->toBeTrue();
    expect(Cache::store('array')->get('broken-links.count'))->toBe(2);

    // Second call, should NOT call the SDK (verified by ->once() in mock)
    expect($service->getBrokenLinksCount())->toBe(2);
});
