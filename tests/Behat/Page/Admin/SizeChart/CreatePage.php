<?php

/*
 * This file is part of package
 * Sylius Size Chart Plugin
 *
 * @copyright MADCODERS Team (www.madcoders.co)
 * @licence For the full copyright and license information, please view the LICENSE
 *
 * Architects of this package:
 * @author Leonid Moshko <l.moshko@madcoders.pl>
 * @author Piotr Lewandowski <p.lewandowski@madcoders.pl>
 */

declare(strict_types=1);

namespace Tests\Madcoders\SyliusSizechartPlugin\Behat\Page\Admin\SizeChart;

use Behat\Mink\Element\NodeElement;
use Behat\Mink\Exception\ElementNotFoundException;
use Sylius\Behat\Behaviour\Toggles;
use Sylius\Behat\Page\Admin\Crud\CreatePage as BaseCreatePage;
use Tests\Madcoders\SyliusSizechartPlugin\Behat\Behaviour\ChoosesFormElement;
use Tests\Madcoders\SyliusSizechartPlugin\Behat\Behaviour\TogglesForCriteriaOptions;

class CreatePage extends BaseCreatePage implements CreatePageInterface
{
    use ChoosesFormElement;

    use Toggles;

    use TogglesForCriteriaOptions;

    /**
     * @throws ElementNotFoundException
     */
    protected function getToggleableElement(): NodeElement
    {
        return $this->getElement('enabled');
    }

    protected function getToggleableElementForCriteriaOption(string  $code): NodeElement
    {
        return $this->findElementByValue($code);
    }

    protected function getToggleableElementsForCriteriaOptions(): array
    {
        return [];
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'enabled' => '#size_chart_enabled',
        ]);
    }

    public function attachFile(string $path, string $localeCode): void
    {
        $filesPath = $this->getParameter('files_path');
        $translationPrefix = '#size_chart_translations_'. $localeCode . '_file';

        $this->getDocument()->find('css', $translationPrefix)->attachFile($filesPath . $path);
    }

    private function findElementByValue(string $code): NodeElement
    {
        if (!$element = $this->getDocument()->find('css', '[value="'.$code.'"]')) {
            throw new \InvalidArgumentException(sprintf(
                'Attribute toggle element with code "%s" could not be found',
                $code
            ));
        }

        return $element;
    }
}
