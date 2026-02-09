<?php

namespace Code16\SharpOhdearBrokenLinks\Sharp\BrokenLinks;

use Cache;
use Code16\Sharp\EntityList\Fields\EntityListField;
use Code16\Sharp\EntityList\Fields\EntityListFieldsContainer;
use Code16\Sharp\EntityList\Fields\EntityListFieldsLayout;
use Code16\Sharp\EntityList\SharpEntityList;
use Code16\Sharp\Exceptions\Form\SharpApplicativeException;
use Code16\SharpOhdearBrokenLinks\Exceptions\MonitorIdIsNotDefinedException;
use Code16\SharpOhdearBrokenLinks\Exceptions\UnableToGetOhDearDataException;
use Code16\SharpOhdearBrokenLinks\Services\BrokenLinksService;
use Illuminate\Contracts\Support\Arrayable;
use OhDear\PhpSdk\OhDear;
use Throwable;

class BrokenLinkList extends SharpEntityList
{
    protected function buildList(EntityListFieldsContainer $fields): void
    {
        $fields
            ->addField(
                EntityListField::make('status_code')
                    ->setLabel('Code d’erreur')
                    ->setWidth(2),
            )
            ->addField(
                EntityListField::make('errored_url')
                    ->setLabel('Url')
                    ->setWidth(5),
            )
            ->addField(
                EntityListField::make('found_on_url')
                    ->setLabel('Trouvé sur')
                    ->setWidth(5),
            );
    }

    public function getListData(): array|Arrayable
    {
        if(Cache::has('broken-links.count')) {
            // busting count cache when refreshing the broken-links list
            Cache::forget('broken-links.count');
        }

        try {
            $brokenLinks = BrokenLinksService::make()->getBrokenLinks();
        }  catch(MonitorIdIsNotDefinedException $e) {
            report($e);
            throw new SharpApplicativeException("The monitor ID is not defined, please check your environment or configuration.");
        } catch(UnableToGetOhDearDataException|\Throwable $e) {
            if (!$e instanceof UnableToGetOhDearDataException) {
                report($e);
            }

            throw new SharpApplicativeException("An exception was thrown while fetching broken links from OhDear. See logs for more details.");
        }

        return $this
            ->setCustomTransformer('status_code', function ($value, $brokenLink) {
                return $brokenLink->statusCode;
            })
            ->setCustomTransformer('errored_url', function ($value, $brokenLink) {
                return sprintf(
                    '<a href="%s" target="_blank">%s</a>',
                    $brokenLink->crawledUrl,
                    $brokenLink->crawledUrl,
                );
            })
            ->setCustomTransformer('found_on_url', function ($value, $brokenLink) {
                return sprintf(
                    '<a href="%s" target="_blank">%s</a>',
                    $brokenLink->foundOnUrl,
                    $brokenLink->foundOnUrl,
                );
            })
            ->transform(collect($brokenLinks)->toArray());
    }
}
