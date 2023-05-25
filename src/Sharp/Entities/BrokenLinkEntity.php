<?php

namespace Code16\SharpOhdearBrokenLinks\Sharp\Entities;

use App\Sharp\User\UserList;
use Code16\Sharp\Utils\Entities\SharpEntity;

class BrokenLinkEntity extends SharpEntity
{
    protected string $label = 'Liens cassés';

    protected ?string $list = UserList::class;
}
