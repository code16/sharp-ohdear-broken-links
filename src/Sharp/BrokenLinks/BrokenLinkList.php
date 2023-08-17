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
    public function buildListFields(EntityListFieldsContainer $fieldsContainer): void
    {
        $fieldsContainer
            ->addField(
                EntityListField::make('status_code')
                    ->setLabel('Code d’erreur')
            )
            ->addField(
                EntityListField::make('errored_url')
                    ->setLabel('Url')
            )
            ->addField(
                EntityListField::make('found_on_url')
                    ->setLabel('Trouvé sur')
            );
    }

    public function buildListLayout(EntityListFieldsLayout $fieldsLayout): void
    {
        $fieldsLayout
            ->addColumn('status_code', 2)
            ->addColumn('errored_url', 5)
            ->addColumn('found_on_url', 5);
    }

    public function buildListLayoutForSmallScreens(EntityListFieldsLayout $fieldsLayout): void
    {
        $fieldsLayout
            ->addColumn('status_code', 4)
            ->addColumn('errored_url', 4)
            ->addColumn('found_on_url', 4);
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
                        config('schedule-monitor.oh_dear.site_id')
                    )
            );
    }
}
