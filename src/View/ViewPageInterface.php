<?php

namespace App\View;

interface ViewPageInterface {
    /** @return array<string, callable> */
    public function getExceptionCallbacks(): array;

    public function getDefaultTemplate(): string;
}
