<?php

namespace App\View\Components\Dashboard\Form;

use App\View\Components\Dashboard\Base;
use Illuminate\Contracts\View\View;

class FormMl extends Base
{
    public ?string $showStatus;

    public string $action;

    public string $indexUrl;

    public string $method;

    public string $viewMode;

    public function __construct(
        ?string $showStatus = null,
        string $action = '',
        string $indexUrl = '',
        string $method = '',
        string $viewMode = 'add'
    ) {
        $this->showStatus = $showStatus;
        $this->action = $action;
        $this->indexUrl = $indexUrl;
        $this->method = $method;
        $this->viewMode = $viewMode;
    }

    public function renderMlHtml(string $slot, string $lngCode, mixed $mlData = null): void
    {
        $mlForm = new MlTabs();

        $mlForm->renderHtml($slot, $lngCode, $mlData);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return $this->dashboardComponent('form._form_ml');
    }
}
