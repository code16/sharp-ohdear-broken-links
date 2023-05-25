<?php

namespace Code16\SharpOhdearBrokenLinks\Sharp\Entities;

use App\Sharp\User\UserForm;
use App\Sharp\User\UserList;
use App\Sharp\User\UserPolicy;
use App\Sharp\User\UserShow;
use Code16\Sharp\Utils\Entities\SharpEntity;

class BrokenLinkEntity extends SharpEntity
{
    protected string $label = 'Liens cassés';
    protected ?string $list = UserList::class;
}
