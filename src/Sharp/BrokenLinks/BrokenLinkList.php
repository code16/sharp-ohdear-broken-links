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
                    ->setLabel('Code d\'erreur')
            )
            ->addField(
                EntityListField::make('errored_url')
                    ->setLabel('Url')
            );
    }

    public function buildListLayout(EntityListFieldsLayout $fieldsLayout): void
    {
        $fieldsLayout
            ->addColumn('status_code', 2)
            ->addColumn('errored_url', 10);
    }

    public function buildListLayoutForSmallScreens(EntityListFieldsLayout $fieldsLayout): void
    {
        $fieldsLayout
            ->addColumn('status_code', 4)
            ->addColumn('errored_url', 8);
    }

    public function buildListConfig(): void
    {
    }

    public function getListData(): array|Arrayable
    {
        $ohDear = app(OhDear::class);

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
            ->transform(
                $ohDear->brokenLinks(
                    config('schedule-monitor.oh_dear.site_id')
                )
            );
    }
}
