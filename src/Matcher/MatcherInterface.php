<?php

/*
 * This file is part of package:
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

namespace Madcoders\SyliusSizechartPlugin\Matcher;

use Madcoders\SyliusSizechartPlugin\Entity\SizeChartInterface;
use Sylius\Component\Core\Model\ProductInterface;

interface MatcherInterface
{
    /**
     * @param ProductInterface $product
     *
     * @return SizeChartInterface[]
     */
    public function match(ProductInterface $product): array;
}
