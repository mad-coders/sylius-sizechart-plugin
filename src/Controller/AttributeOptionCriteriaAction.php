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

namespace Madcoders\SyliusSizechartPlugin\Controller;

use Madcoders\SyliusSizechartPlugin\Exception\AttributeNotFoundException;
use Madcoders\SyliusSizechartPlugin\Form\Factory\AttributeOptionCriteriaFactoryInterface;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;

final class AttributeOptionCriteriaAction
{
    /**
     * @var AttributeOptionCriteriaFactoryInterface
     */
    private $attributeOptionCriteriaFactory;
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(
        AttributeOptionCriteriaFactoryInterface $attributeOptionCriteriaFactory,
        Environment $twig
    )
    {
        $this->attributeOptionCriteriaFactory = $attributeOptionCriteriaFactory;
        $this->twig = $twig;
    }

    public function __invoke(string $attributeCode, Request $request): Response
    {
        /** @var string $template */
        $template = $request->attributes->get(
            'attribute_option_criteria_template',
            '@MadcodersSyliusSizechartPlugin/Admin/Form/attributeOptionCriteria.html.twig'
        );

        try {
            $formView = $this->attributeOptionCriteriaFactory->createAttributeOptionCriteriaFormView($attributeCode);
        } catch (AttributeNotFoundException $e) {
            throw new NotFoundHttpException(
                sprintf('Attribute with code "%s" has not been found', $e->getAttributeCode()),
                $e
            );
        }

        return new Response($this->twig->render($template, ['form' => $formView]));
    }


}
