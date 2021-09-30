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

namespace Madcoders\SyliusSizechartPlugin\Form\Provider;

use Sylius\Bundle\ProductBundle\Doctrine\ORM\ProductAttributeValueRepository;
use Sylius\Component\Attribute\AttributeType\IntegerAttributeType;
use Sylius\Component\Attribute\AttributeType\SelectAttributeType;
use Sylius\Component\Attribute\AttributeType\TextAttributeType;
use Sylius\Component\Product\Model\ProductAttributeInterface;
use Sylius\Component\Product\Model\ProductAttributeValueInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class AttributeOptionsProvider implements AttributeOptionsProviderInterface
{
    /** @var ProductAttributeValueRepository  */
    private $productAttributeValueRepository;

    /** @var RepositoryInterface  */
    private $productAttributeRepository;

    /** @var string */
    private $localeCode;

    public function __construct(
        ProductAttributeValueRepository $productAttributeValueRepository,
        RepositoryInterface $productAttributeRepository,
        string $localeCode
    )
    {
        $this->productAttributeValueRepository = $productAttributeValueRepository;
        $this->productAttributeRepository = $productAttributeRepository;
        $this->localeCode = $localeCode;
    }

    public function provideOptionsByAttributeCode(string $code): array
    {
        if (!$attribute = $this->getAttribute($code)) {
            return [];
        }

        $qb = $this->productAttributeValueRepository->createQueryBuilder('av');

        $qb->andWhere('av.attribute = :attribute');
        $qb->andWhere('av.localeCode = :localeCode');
        $qb->setParameter(':attribute', $attribute->getId());
        $qb->setParameter(':localeCode', $this->localeCode);

        $choices = [];
        foreach($qb->getQuery()->getResult() as $value) {
            /** @var ProductAttributeValueInterface $value */
            switch($value->getType()) {
                case SelectAttributeType::TYPE:
                    /** @var string[] $values */
                    $values = $value->getValue();
                    foreach($values as $index) {
                        if (!$value->getAttribute()) {
                            continue;
                        }
                        $config = $value->getAttribute()->getConfiguration();
                        /** @var array $attributeChoices */
                        $attributeChoices = $config['choices'] ?? [];
                        $label = (string)($attributeChoices[$index][$this->localeCode] ?? '');
                        $choices[$label] = $index;
                    }
                    break;
                case IntegerAttributeType::TYPE:
                case TextAttributeType::TYPE:
                default:
                $textValue = (string)$value->getValue();
                $choices[$textValue] = $textValue;
                break;
            }
        }

        return $choices;
    }

    private function getAttribute(string $code): ?ProductAttributeInterface
    {
        $attribute = $this->productAttributeRepository->findOneBy(['code' => $code]);
        if (!$attribute instanceof ProductAttributeInterface) {
            return null;
        }

        return $attribute;
    }
}
