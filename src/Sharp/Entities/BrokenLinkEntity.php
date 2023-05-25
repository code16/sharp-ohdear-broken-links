<?php

namespace Code16\SharpOhdearBrokenLinks\Sharp\Entities;

use Code16\Sharp\Utils\Entities\SharpEntity;
use Code16\SharpOhdearBrokenLinks\Sharp\BrokenLinks\BrokenLinkList;

class BrokenLinkEntity extends SharpEntity
{
    protected string $label = 'Liens cassés';

    protected ?string $list = BrokenLinkList::class;
    protected array $prohibitedActions = ['create', 'view', 'update', 'delete'];

}
