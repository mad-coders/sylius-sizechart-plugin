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

use Behat\Mink\Exception\ElementNotFoundException;
use Sylius\Behat\Page\Admin\Crud\CreatePageInterface as BaseCreatePageInterface;

interface CreatePageInterface extends BaseCreatePageInterface
{
    /**
     * @throws ElementNotFoundException
     */
    public function choosesFormElement(string $name, string $element): void;

    public function enable(): void;

    public function disable(): void;

    public function enableCriteriaOptions(): void;

    public function disableCriteriaOptions(): void;

    public function enableCriteriaOption(string $code): void;

    public function disableCriteriaOption(string $code): void;

    public function attachFile(string $path, string $localeCode): void;
}
