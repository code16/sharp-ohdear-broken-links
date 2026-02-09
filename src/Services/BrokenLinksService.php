<?php

namespace Code16\SharpOhdearBrokenLinks\Services;

use Cache;
use Code16\SharpOhdearBrokenLinks\Exceptions\MonitorIdIsNotDefinedException;
use Code16\SharpOhdearBrokenLinks\Exceptions\UnableToGetOhDearDataException;
use Exception;
use OhDear\PhpSdk\Dto\BrokenLink;
use OhDear\PhpSdk\OhDear;

class BrokenLinksService
{
    protected OhDear $client;

    /**
     * @throws UnableToGetOhDearDataException
     */
    public function __construct(){
        try {
            $this->client = app(OhDear::class, [
                'apiToken' => config()->string('broken-links.api_token', ''),
            ]);
        } catch(Exception $exception) {
            report($exception);
            throw new UnableToGetOhDearDataException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    public static function make(): self
    {
        return new static();
    }

    /**
     * @return iterable|BrokenLink[]
     * @throws MonitorIdIsNotDefinedException
     */
    public function getBrokenLinks(): iterable
    {
        if(!config('broken-links.monitor_id')) {
            throw new MonitorIdIsNotDefinedException();
        }

        return $this->client->brokenLinks((int) config('broken-links.monitor_id'));
    }

    public function getBrokenLinksCount(): int
    {
        return Cache::remember(
            key: 'broken-links.count',
            ttl: config('broken-links.cache.count'),
            callback: fn() => collect($this->getBrokenLinks() ?? [])->count()
        );
    }
}
