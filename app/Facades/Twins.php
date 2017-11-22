<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Twins extends Facade {
    protected static function getFacadeAccessor() {
        return 'twins';
    }
}
