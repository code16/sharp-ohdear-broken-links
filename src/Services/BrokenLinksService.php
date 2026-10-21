<?php

namespace Code16\SharpOhdearBrokenLinks\Services;

use Cache;
use Code16\SharpOhdearBrokenLinks\Exceptions\MonitorIdIsNotDefinedException;
use Code16\SharpOhdearBrokenLinks\Exceptions\UnableToGetOhDearDataException;
use Exception;
use OhDear\PhpSdk\Dto\BrokenLink;
use OhDear\PhpSdk\OhDear;
use Throwable;

class BrokenLinksService
{
    protected ?OhDear $client = null;

    /**
     * The client is resolved lazily so that make() never throws.
     *
     * @throws UnableToGetOhDearDataException
     */
    protected function client(): OhDear
    {
        try {
            return $this->client ??= app(OhDear::class, [
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

    public static function isConfigured(): bool
    {
        return
            !empty(config('broken-links.api_token'))
            && !empty(config('broken-links.monitor_id'));
    }

    /**
     * @return iterable|BrokenLink[]
     * @throws MonitorIdIsNotDefinedException
     * @throws UnableToGetOhDearDataException
     */
    public function getBrokenLinks(): iterable
    {
        if(!config('broken-links.monitor_id')) {
            throw new MonitorIdIsNotDefinedException();
        }

        return $this->client()->brokenLinks((int) config('broken-links.monitor_id'));
    }

    /**
     * Returns null if the count can't be fetched (exception is reported), so it can safely be used in a menu badge.
     */
    public function getBrokenLinksCount(): ?int
    {
        try {
            return Cache::remember(
                key: 'broken-links.count',
                ttl: config('broken-links.cache.count'),
                callback: fn() => collect($this->getBrokenLinks() ?? [])->count()
            );
        } catch(Throwable $exception) {
            report($exception);

            return null;
        }
    }
}
