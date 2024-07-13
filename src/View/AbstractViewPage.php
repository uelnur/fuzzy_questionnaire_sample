<?php

namespace App\View;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AbstractViewPage extends AbstractController implements ViewPageInterface {
    protected string $defaultTemplate = '';

    public function getExceptionCallbacks(): array {
        return [];
    }

    public function getDefaultTemplate(): string {
        return $this->defaultTemplate;
    }
}
