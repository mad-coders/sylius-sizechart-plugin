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

use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

final class SizeChartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('enabled', CheckboxType::class, [
                'required' => false,
                'label' => 'madcoders_sizechart.admin.size_chart.form.enabled',
            ])
            ->add('criteria', SizeChartCriteriaType::class, [
                'required' => false,
                'label' => ' ',
                'allow_extra_fields' => true,
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => SizeChartTranslationType::class,
                'label' => 'madcoders_sizechart.admin.size_chart.form.name',
            ])

            ->addEventSubscriber(new AddCodeFormSubscriber(
                NULL,
                [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'madcoders_sizechart.validator.not_blank',
                        ])
                    ],
                ]
            ));
    }

    public function getBlockPrefix(): string
    {
        return 'size_chart';
    }
}
