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
use Sylius\Component\Product\Model\ProductAttributeInterface;

final class SizeChartValidator implements SizeChartValidatorInterface
{
    /** @var string */
    private $localeCode;

    public function __construct(
        string $localeCode
    )
    {
        $this->localeCode = $localeCode;
    }

    public function isValidForTheProduct(SizeChartInterface $sizeChart, ProductInterface $product): bool
    {
        $criteria = $sizeChart->getCriteria();

        /** @var string[] $attributeCodes */
        $attributeCodes = $criteria['attributes'] ?? [];

        foreach($attributeCodes as $attributeCode) {
            /** @var string $value */
            $value = $criteria['attribute' . $attributeCode ] ?? '';
            $productAttributeValue = $product->getAttributeByCodeAndLocale($attributeCode, $this->localeCode);

            if (!$productAttributeValue || $productAttributeValue->getValue() !== $value) {
                return false;
            }
        }

        return true;
    }
}
