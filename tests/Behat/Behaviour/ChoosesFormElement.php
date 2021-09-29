<?php

declare(strict_types=1);

namespace Tests\Madcoders\SyliusSizechartPlugin\Behat\Behaviour;

use Sylius\Behat\Behaviour\DocumentAccessor;

/**
 * Sylius Sizechart Plugin
 *
 * @copyright MADCODERS Team (www.madcoders.co)
 * @licence For the full copyright and license information, please view the LICENSE
 *
 * Architects of this package:
 * @author Leonid Moshko <l.moshko@madcoders.pl>
 * @author Piotr Lewandowski <p.lewandowski@madcoders.pl>
 */
trait ChoosesFormElement
{
    use DocumentAccessor;

    public function choosesFormElement(string $name, string $element): void
    {
        $this->getDocument()->fillField($element, $name);
    }
}
