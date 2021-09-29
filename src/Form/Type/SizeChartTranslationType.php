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

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class SizeChartTranslationType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'madcoders_sizechart.admin.size_chart.form.name',
            ])
            ->add('file', FileType::class, [
                'required' => false,
                'label' => 'madcoders_sizechart.admin.size_chart.form.pdf',
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'madcoders_sizechart.admin.size_chart.form.description',
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'size_chart_translation';
    }
}
