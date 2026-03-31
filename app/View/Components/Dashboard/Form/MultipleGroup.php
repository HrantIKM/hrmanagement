<?php

namespace App\View\Components\Dashboard\Form;

use App\View\Components\Dashboard\Base;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMNodeList;
use DOMXPath;
use Illuminate\Contracts\View\View;

class MultipleGroup extends Base
{
    public string $class;

    public string $index;

    public mixed $multipleData;

    public array $groupData;

    public $xpath;

    public const ATTRIBUTE_NAME = 'name';

    public const ATTRIBUTE_DATA_NAME = 'data-name';

    public const TAG_INPUT = 'input';

    public const TAG_TEXTAREA = 'textarea';

    public const TAG_SELECT = 'select';

    public const TAG_CHECKBOX = 'checkbox';

    public function __construct(
        string $class = '',
        string $index = '0',
        mixed $multipleData = []
    ) {
        $this->class = $class;
        $this->index = $index;
        $this->multipleData = $multipleData;
        $this->groupData = $multipleData;
    }

    /**
     * Function to render html.
     */
    public function renderHtml(string $html, mixed $multipleData, string $index): void
    {
        $this->multipleData = $multipleData;
        $this->index = $index;
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        $this->xpath = $xpath;

        $this->find($xpath, '//span[@data-name]', self::ATTRIBUTE_DATA_NAME);
        $this->find($xpath, '//input[@name]');
        $this->find($xpath, '//textarea[@name]');
        $this->find($xpath, '//select[@name]');

        echo $dom->saveHTML();
    }

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
            $replacedName = replaceNameWithDots($name);

            if ($attribute == self::ATTRIBUTE_DATA_NAME) {
                $newValue = str_replace('0', $this->index, $replacedName);
            } else {
                $name = $input->getAttribute($attribute);

                if (!empty($this->multipleData)) {
                    $this->setValue($input, $input->getAttribute('data-name'));
                }

                $newValue = str_replace('0', $this->index, $name);
            }

            $input->setAttribute($attribute, $newValue);
        }
    }

    /**
     * Function to element set value.
     */
    private function setValue(DOMElement|DOMNode $input, mixed $name): void
    {
        $columnValue = $this->multipleData;
        if ($name) {
            $columnValue = $this->multipleData[$name] ?? '';
        }

        $tagName = $input->tagName;
        if ($input->getAttribute('type') === self::TAG_CHECKBOX) {
            $tagName = self::TAG_CHECKBOX;
        }

        switch ($tagName) {
            case self::TAG_INPUT:
                $input->setAttribute('value', $columnValue);
                break;

            case self::TAG_TEXTAREA:
                $input->textContent = $columnValue;
                break;

            case self::TAG_CHECKBOX:
                if ($columnValue) {
                    $input->setAttribute('checked', true);
                }

                $inputId = $input->getAttribute('id');
                $newId = $input->getAttribute('name') . '_' . rand();
                $label = $this->xpath->query("//label[@for='$inputId']")->item(0);

                $label->setAttribute('for', $newId);
                $input->setAttribute('id', $newId);

                break;

            case self::TAG_SELECT:
                $input->setAttribute('id', $input->getAttribute('id') . '_' . rand());
                $optionTags = $input->getElementsByTagName('option');
                foreach ($optionTags as $tag) {
                    if ($tag->getAttribute('value') == $columnValue) {
                        $tag->setAttribute('selected', 'selected');
                    }
                }
                break;
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return $this->dashboardComponent('form._multiple_group');
    }
}
