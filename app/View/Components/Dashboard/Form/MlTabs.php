<?php

namespace App\View\Components\Dashboard\Form;

use App\View\Components\Dashboard\Base;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMNodeList;
use DOMXPath;
use Illuminate\Contracts\View\View;

class MlTabs extends Base
{
    /**
     * @var string
     */
    public const ATTRIBUTE_NAME = 'name';

    public const ATTRIBUTE_DATA_NAME = 'data-name';

    /**
     * @var string
     */
    public const TAG_INPUT = 'input';

    public const TAG_TEXTAREA = 'textarea';

    protected string $lngCode;

    protected mixed $mlData;

    /**
     * Function to find by selector and change html tag.
     */
    private function find(DOMXPath $xpath, string $selector, string $attribute = self::ATTRIBUTE_NAME): void
    {
        $elements = $xpath->query($selector);
        $this->changeNameAndSetValue($elements, $attribute);
    }

    /**
     * Function to change html tag name and set value.
     */
    private function changeNameAndSetValue(DOMNodeList $inputs, string $attribute): void
    {
        foreach ($inputs as $input) {
            $name = $input->getAttribute($attribute);
            if ($this->mlData && $this->mlData->count()) {
                $this->setValue($input, $name);
            }

            $multipleName = '';
            if (($pos = strpos($name, '[')) !== false) {
                $multipleName = substr($name, $pos);
                $name = explode('[', $name, 2)[0];
            }

            if ($attribute == self::ATTRIBUTE_NAME) {
                $newValue = "ml[$this->lngCode][$name]$multipleName";
            } else {
                $multipleName = replaceNameWithDots($multipleName);

                $newValue = "ml.$this->lngCode.$name$multipleName";
            }

            $input->setAttribute($attribute, $newValue);
        }
    }

    /**
     * Function to element set value.
     */
    private function setValue(DOMElement|DOMNode $input, string $name): void
    {
        $columnValue = $this->mlData[$this->lngCode]->{$name};
        $tagName = $input->tagName;
        if ($tagName === self::TAG_INPUT) {
            $input->setAttribute('value', $columnValue);
        } elseif ($tagName === self::TAG_TEXTAREA) {
            $input->textContent = $columnValue ?? '';
        }
    }

    /**
     * Function to render html.
     */
    public function renderHtml(string $html, string $lngCode, mixed $mlData = null): void
    {
        $this->lngCode = $lngCode;
        $this->mlData = $mlData;
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $xpath = new DOMXPath($dom);
        $this->find($xpath, '//span[@data-name]', self::ATTRIBUTE_DATA_NAME);
        $this->find($xpath, '//input[@name]');
        $this->find($xpath, '//textarea[@name]');

        echo mb_convert_encoding($dom->saveHTML(), 'UTF-8', 'HTML-ENTITIES');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return $this->dashboardComponent('form._ml_tabs');
    }
}
