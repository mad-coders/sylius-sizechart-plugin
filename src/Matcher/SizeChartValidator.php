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
use Sylius\Component\Attribute\AttributeType\IntegerAttributeType;
use Sylius\Component\Attribute\AttributeType\SelectAttributeType;
use Sylius\Component\Attribute\AttributeType\TextAttributeType;
use Sylius\Component\Core\Model\ProductInterface;

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

        foreach ($attributeCodes as $attributeCode) {
            /** @var string $value */
            $value = $criteria['attribute' . $attributeCode] ?? '';
            $productAttributeValue = $product->getAttributeByCodeAndLocale($attributeCode, $this->localeCode);

            if (!$productAttributeValue) {
                return false;
            }

            if (!$productAttributeValue->getAttribute()) {
                return false;
            }

            switch ($productAttributeValue->getAttribute()->getType()) {
                case SelectAttributeType::TYPE:
                    /** @var array|null $selectAttributeValue */
                    $selectAttributeValue = $productAttributeValue->getValue();
                    if (!is_array($selectAttributeValue)) {
                        return false;
                    }

                    if (!in_array($value, $selectAttributeValue)) {
                        return false;
                    }

                    break;
                case IntegerAttributeType::TYPE:
                case TextAttributeType::TYPE:
                default:
                    if ($productAttributeValue->getValue() !== $value) {
                        return false;
                    }
                    break;
            }
        }

        return true;
    }
}
