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

use Madcoders\SyliusSizechartPlugin\Form\Provider\AttributeOptionsProviderInterface;
use Symfony\Component\Form\ChoiceList\Factory\ChoiceListFactoryInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttributeOptionCriteriaType extends ChoiceType
{
    /** @var AttributeOptionsProviderInterface  */
    private $attributeOptionsProvider;

    public function __construct(
        AttributeOptionsProviderInterface $attributeOptionsProvider,
        ChoiceListFactoryInterface $choiceListFactory = null,
        $translator = null)
    {
        parent::__construct($choiceListFactory, $translator);
        $this->attributeOptionsProvider = $attributeOptionsProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var string $attributeCode */
        $attributeCode = $options['attribute_code'];

        $choices = $this->attributeOptionsProvider->provideOptionsByAttributeCode($attributeCode);
        $options['choices'] = $choices;

        parent::buildForm($builder, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired('attribute_code');
        $resolver->setAllowedTypes('attribute_code', [ 'string' ]);
        $resolver->setDefault('choices', []);
        $resolver->setDefault('placeholder', '---');
        $resolver->setDefault('required', false);
    }
}
