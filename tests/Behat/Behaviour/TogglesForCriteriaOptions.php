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

namespace Tests\Madcoders\SyliusSizechartPlugin\Behat\Behaviour;

use Behat\Mink\Element\NodeElement;

trait TogglesForCriteriaOptions
{
    abstract protected function getToggleableElementsForCriteriaOptions(): array;
    abstract protected function getToggleableElementForCriteriaOption(string  $code): NodeElement;

    /**
     * @throws \RuntimeException If already enabled criteria options
     */
    public function enableCriteriaOptions(): void
    {
        $toggleableElements = $this->getToggleableElementsForCriteriaOptions();

        foreach ($toggleableElements as $toggleableElement) {
            $this->assertCheckboxState($toggleableElement, false);
            $toggleableElement->check();
        }
    }

    /**
     * @throws \RuntimeException If already disabled criteria options
     */
    public function disableCriteriaOptions(): void
    {
        $toggleableElements = $this->getToggleableElementsForCriteriaOptions();

        foreach ($toggleableElements as $toggleableElement) {
            $this->assertCheckboxState($toggleableElement, true);
            $toggleableElement->uncheck();
        }
    }

    public function enableCriteriaOption(string $code): void
    {
        $toggleableElement = $this->getToggleableElementForCriteriaOption($code);
        $this->assertCheckboxState($toggleableElement, false);

        $toggleableElement->check();
    }

    public function disableCriteriaOption(string $code): void
    {
        $toggleableElement = $this->getToggleableElementForCriteriaOption($code);
        $this->assertCheckboxState($toggleableElement, true);

        $toggleableElement->uncheck();
    }
}
