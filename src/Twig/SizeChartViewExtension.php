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

namespace Madcoders\SyliusSizechartPlugin\Twig;

use Madcoders\SyliusSizechartPlugin\Entity\SizeChartInterface;
use Madcoders\SyliusSizechartPlugin\Matcher\MatcherInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Symfony\Component\Templating\EngineInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SizeChartViewExtension extends AbstractExtension
{
    /** @var EngineInterface|Environment */
    private $templatingEngine;

    /** @var MatcherInterface */
    private $matcher;

    /**
     * @param EngineInterface|Environment $templatingEngine
     */
    public function __construct($templatingEngine, MatcherInterface $matcher)
    {
        $this->templatingEngine = $templatingEngine;
        $this->matcher = $matcher;
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('size_chart_links', [$this, 'getSizeChartLinks'], ['is_safe' => ['html']]),
            new TwigFunction('size_chart_link', [$this, 'getSizeChartLink'], ['is_safe' => ['html']]),
        ];
    }

    public function getSizeChartLinks(ProductInterface $product, string $template = null): string
    {
        if (!$template) {
            $template = '@MadcodersSyliusSizechartPlugin/Shop/Sizechart/View/standard.html.twig';
        }

        $sizeCharts = $this->matcher->match($product);

        return $this->templatingEngine->render(
            $template, ['product' => $product, 'sizeCharts' => $sizeCharts]
        );
    }

    public function getSizeChartLink(SizeChartInterface $sizeChart, string $template = null): string
    {
        if (!$template) {
            $template = '@MadcodersSyliusSizechartPlugin/Shop/Sizechart/View/link.html.twig';
        }

        return $this->templatingEngine->render(
            $template, [ 'sizeChart' => $sizeChart ]
        );
    }
}
