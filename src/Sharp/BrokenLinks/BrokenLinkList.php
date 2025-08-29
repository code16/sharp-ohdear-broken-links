<?php

namespace Code16\SharpOhdearBrokenLinks\Sharp\BrokenLinks;

use Code16\Sharp\EntityList\Fields\EntityListField;
use Code16\Sharp\EntityList\Fields\EntityListFieldsContainer;
use Code16\Sharp\EntityList\Fields\EntityListFieldsLayout;
use Code16\Sharp\EntityList\SharpEntityList;
use Illuminate\Contracts\Support\Arrayable;
use OhDear\PhpSdk\OhDear;

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
            ->transform(
                app(OhDear::class)
                    ->brokenLinks(
                        config('schedule-monitor.oh_dear.monitor_id')
                    )
            );
    }
}
