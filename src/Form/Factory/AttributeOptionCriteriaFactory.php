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

namespace Madcoders\SyliusSizechartPlugin\Form\Factory;

use Madcoders\SyliusSizechartPlugin\Exception\AttributeNotFoundException;
use Madcoders\SyliusSizechartPlugin\Form\Type\AttributeOptionCriteriaType;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormView;

final class AttributeOptionCriteriaFactory implements AttributeOptionCriteriaFactoryInterface
{
    /** @var RepositoryInterface  */
    private $attributeRepository;

    /** @var FormFactoryInterface  */
    private $formFactory;

    public function __construct(
        RepositoryInterface $attributeRepository,
        FormFactoryInterface $formFactory
    )
    {
        $this->attributeRepository = $attributeRepository;
        $this->formFactory = $formFactory;
    }

    /**
     * @throws AttributeNotFoundException
     */
    public function createAttributeOptionCriteriaFormView(string $attributeCode): FormView
    {
        /** @var AttributeInterface|null $attribute */
        $attribute = $this->attributeRepository->findOneBy(['code' => $attributeCode]);
        if (!$attribute) {
            throw new AttributeNotFoundException(
                sprintf('Attribute with code "%s" has not been found', $attributeCode),
                $attributeCode
            );
        }

        return $this->formFactory->createNamed(
                'size_chart_attribute_option_prototype_____',
                AttributeOptionCriteriaType::class,
                null,
                [
                    'attribute_code' => $attributeCode,
                    'label' => sprintf('%s (%s)', (string)$attribute->getName(), $attributeCode),
                ]
            )
            ->createView();
    }
}
