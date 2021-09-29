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

namespace Madcoders\SyliusSizechartPlugin\Form\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Bundle\ProductBundle\Form\Type\ProductAttributeChoiceType;
use Sylius\Component\Product\Model\ProductAttributeInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

final class SizeChartCriteriaType extends AbstractType
{
    /** @var RepositoryInterface */
    private $productAttributeRepository;

    public function __construct(
        RepositoryInterface $productAttributeRepository
    )
    {
        $this->productAttributeRepository = $productAttributeRepository;
    }


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('attributes', ProductAttributeChoiceType::class, [
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'label' => 'madcoders_sizechart.admin.size_chart.form.attributes',
            ]);

        $builder->addModelTransformer(new CallbackTransformer(
            function (array $data) {
                $collection = new ArrayCollection();
                /** @var string[] $attributeCodes */
                $attributeCodes = $data['attributes'] ?? [];
                foreach($attributeCodes as $attributeCode) {
                    /** @var ProductAttributeInterface|null $attribute */
                    $attribute = $this->productAttributeRepository->findOneBy([ 'code' => $attributeCode]);
                    if ($attribute) {
                        $collection->add($attribute);
                    }
                }

                $data['attributes'] = $collection;

                return $data;
            },
            function (array $data) {
                /** @var ProductAttributeInterface[] $attributes */
                $attributes = $data['attributes'] ?? new ArrayCollection();
                $attributeCodes = [];
                foreach ($attributes as $attribute) {
                    $attributeCodes[] = $attribute->getCode();
                }

                $data['attributes'] = $attributeCodes;

                return $data;
            }
        ));

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
            /** @var array $data */
            $data = $event->getData();
            $form = $event->getForm();

            /** @var string[] $attributeCodes */
            $attributeCodes = $data['attributes'] ?? [];

            foreach($attributeCodes as $attributeCode) {
                /** @var ProductAttributeInterface|null $attribute */
                $attribute = $this->productAttributeRepository->findOneBy([ 'code' => $attributeCode ]);

                if (!$attribute instanceof ProductAttributeInterface) {
                    continue;
                }

                if (!$form->offsetExists('attribute' . $attributeCode)) {
                    $form->add('attribute' . $attributeCode, AttributeOptionCriteriaType::class, [
                        'attribute_code' => $attributeCode,
                        'label' => sprintf('%s (%s)', (string)$attribute->getName(), (string)$attribute->getCode()),
                    ]);
                }
            }

        });

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
            /** @var array $data */
            $data = $event->getData();
            $form = $event->getForm();

            if (!$data|| !isset($data['attributes']) || !is_array($data['attributes'])) {
                return;
            }

            /** @var string[] $attributeCodes */
            $attributeCodes = $data['attributes'];

            foreach ($attributeCodes as $attributeCode) {
                /** @var ProductAttributeInterface|null $attribute */
                $attribute = $this->productAttributeRepository->findOneBy([ 'code' => $attributeCode]);

                if (!$attribute instanceof ProductAttributeInterface) {
                    continue;
                }

                $form->add('attribute' . $attributeCode, AttributeOptionCriteriaType::class, [
                    'attribute_code' => $attributeCode,
                    'label' => sprintf('%s (%s)', (string)$attribute->getName(), (string)$attribute->getCode()),
                ]);
            }
        });
    }

    public function getBlockPrefix(): string
    {
        return 'size_chart_criteria';
    }
}
