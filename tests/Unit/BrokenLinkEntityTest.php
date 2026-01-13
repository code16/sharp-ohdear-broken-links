<?php

use Code16\SharpOhdearBrokenLinks\Sharp\BrokenLinks\BrokenLinkList;
use Code16\SharpOhdearBrokenLinks\Sharp\Entities\BrokenLinkEntity;

test('BrokenLinkEntity is configured correctly', function () {
    $entity = app(BrokenLinkEntity::class);

    expect($entity->getListOrFail())->toBeInstanceOf(BrokenLinkList::class)
        ->and($entity->isActionProhibited('create'))->toBeTrue()
        ->and($entity->isActionProhibited('view'))->toBeTrue()
        ->and($entity->isActionProhibited('update'))->toBeTrue()
        ->and($entity->isActionProhibited('delete'))->toBeTrue();
});
