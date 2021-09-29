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
use Sylius\Component\Product\Model\ProductAttributeInterface;
use Sylius\Component\Product\Model\ProductAttributeValueInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class AttributeOptionsProvider implements AttributeOptionsProviderInterface
{
    /** @var ProductAttributeValueRepository  */
    private $productAttributeValueRepository;

    /** @var RepositoryInterface  */
    private $productAttributeRepository;

    public function __construct(
        ProductAttributeValueRepository $productAttributeValueRepository,
        RepositoryInterface $productAttributeRepository
    )
    {
        $this->productAttributeValueRepository = $productAttributeValueRepository;
        $this->productAttributeRepository = $productAttributeRepository;
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
        $qb->setParameter(':localeCode', 'en_US');

        $choices = [];
        foreach($qb->getQuery()->getResult() as $value) {
            /** @var ProductAttributeValueInterface $value */
            $index = (string)$value->getValue();
            $choices[$index] = $value->getValue();
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
