<?php

use Code16\SharpOhdearBrokenLinks\Sharp\BrokenLinks\BrokenLinkList;
use Code16\Sharp\Exceptions\Form\SharpApplicativeException;
use OhDear\PhpSdk\Dto\BrokenLink;
use OhDear\PhpSdk\OhDear;
use Illuminate\Support\Facades\Cache;

it('returns broken links transformed for Sharp', function () {
    $mock = Mockery::mock(OhDear::class);
    $mock->shouldReceive('brokenLinks')
        ->with(123)
        ->andReturn([
            new BrokenLink(404, 'https://example.com/broken', '/broken', 'https://example.com/page', '/page', 'Broken link', true),
        ]);

    app()->bind(OhDear::class, function ($app, $parameters) use ($mock) {
        expect($parameters['apiToken'])->toBe('fake-token');
        return $mock;
    });

    config()->set('broken-links.monitor_id', 123);
    config()->set('broken-links.api_token', 'fake-token');

    $list = app(BrokenLinkList::class);
    $data = $list->getListData();

    expect($data)->toBeArray()
        ->and($data)->toHaveCount(1)
        ->and($data[0])->toHaveKey('status_code', 404)
        ->and($data[0])->toHaveKey('errored_url', '<a href="https://example.com/broken" target="_blank">https://example.com/broken</a>')
        ->and($data[0])->toHaveKey('found_on_url', '<a href="https://example.com/page" target="_blank">https://example.com/page</a>');
});

it('clears the broken links count cache when fetching the list', function () {
    config()->set('broken-links.monitor_id', 123);
    config()->set('broken-links.api_token', 'fake-token');
    config()->set('cache.default', 'array');
    Cache::store('array')->put('broken-links.count', 10);

    $mock = Mockery::mock(OhDear::class);
    $mock->shouldReceive('brokenLinks')->andReturn([]);
    app()->bind(OhDear::class, fn() => $mock);

    $list = app(BrokenLinkList::class);
    $list->getListData();

    expect(Cache::store('array')->has('broken-links.count'))->toBeFalse();
});

it('throws a SharpApplicativeException if OhDear API fails', function () {
    $mock = Mockery::mock(OhDear::class);
    $mock
        ->shouldReceive('brokenLinks')
        ->andThrow(new Exception('API error'));

    app()->bind(OhDear::class, function () use ($mock) {
        return $mock;
    });

    $list = app(BrokenLinkList::class);

    expect(fn() => $list->getListData())->toThrow(SharpApplicativeException::class);
});
