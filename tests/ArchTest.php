<?php

test('globals')
    ->expect(['dd', 'dump', 'ray', 'var_dump', 'print_r'])
    ->not->toBeUsed();
