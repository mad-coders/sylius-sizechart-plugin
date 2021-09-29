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

namespace Madcoders\SyliusSizechartPlugin\DependencyInjection;

use Madcoders\SyliusSizechartPlugin\Entity\SizeChart;
use Madcoders\SyliusSizechartPlugin\Entity\SizeChartInterface;
use Madcoders\SyliusSizechartPlugin\Entity\SizeChartTranslation;
use Madcoders\SyliusSizechartPlugin\Entity\SizeChartTranslationInterface;
use Madcoders\SyliusSizechartPlugin\Form\Type\SizeChartType;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Resource\Factory\Factory;
use Sylius\Component\Resource\Factory\TranslatableFactory;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('madcoders_sizechart');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()

                    ->children()

                        ->arrayNode('size_chart')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                    ->scalarNode('model')->defaultValue(SizeChart::class)->cannotBeEmpty()->end()
                                    ->scalarNode('interface')->defaultValue(SizeChartInterface::class)->cannotBeEmpty()->end()
                                    ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                    ->scalarNode('factory')->defaultValue(TranslatableFactory::class)->end()
                                    ->scalarNode('repository')->cannotBeEmpty()->end()
                                    ->scalarNode('form')->defaultValue(SizeChartType::class)->cannotBeEmpty()->end()
                                ->end()
                                ->end()
                                ->arrayNode('translation')
                                ->addDefaultsIfNotSet()
                                    ->children()
                                    ->variableNode('options')->end()
                                    ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(SizeChartTranslation::class)->cannotBeEmpty()->end()
                                        ->scalarNode('interface')->defaultValue(SizeChartTranslationInterface::class)->cannotBeEmpty()->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->cannotBeEmpty()->end()
                                    ->end()
                                    ->end()
                                ->end()
                                ->end()
                            ->end()
                        ->end()

                    ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
