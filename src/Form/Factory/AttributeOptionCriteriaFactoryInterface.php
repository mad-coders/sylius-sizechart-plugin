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

use Gedmo\Tree\RepositoryInterface;
use Madcoders\SyliusSizechartPlugin\Exception\AttributeNotFoundException;
use Madcoders\SyliusSizechartPlugin\Form\Type\AttributeOptionCriteriaType;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormView;

interface AttributeOptionCriteriaFactoryInterface
{
    /**
     * @throws AttributeNotFoundException
     */
    public function createAttributeOptionCriteriaFormView(string $attributeCode): FormView;
}
