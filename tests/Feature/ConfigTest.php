<?php

test('config is registered', function () {
    expect(config('broken-links'))->not->toBeNull()
        ->and(config('broken-links.api_token'))->toBe(env('OH_DEAR_API_TOKEN'))
        ->and(config('broken-links.monitor_id'))->toBe(env('OH_DEAR_MONITOR_ID', env('OH_DEAR_SITE_ID')));
});
