<?php

namespace Code16\SharpOhdearBrokenLinks\Sharp\BrokenLinks;

use Code16\Sharp\EntityList\Fields\EntityListField;
use Code16\Sharp\EntityList\Fields\EntityListFieldsContainer;
use Code16\Sharp\EntityList\Fields\EntityListFieldsLayout;
use Code16\Sharp\EntityList\SharpEntityList;
use Code16\Sharp\Exceptions\Form\SharpApplicativeException;
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

    public function buildListConfig(): void
    {
    }

    public function getListData(): array|Arrayable
    {
        try {
            $brokenLinks = app(OhDear::class, [
                'apiToken' => config()->string('broken-links.api_token', ''),
            ])->brokenLinks((int) config('broken-links.monitor_id', 0));
        } catch(Throwable $e) {
            report($e);
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
