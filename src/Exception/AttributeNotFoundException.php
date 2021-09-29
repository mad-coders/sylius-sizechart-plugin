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

namespace Madcoders\SyliusSizechartPlugin\Exception;

use Exception;
use Throwable;

class AttributeNotFoundException extends Exception
{
    /** @var string */
    private $attributeCode;

    public function __construct(string $message = '', string $attributeCode = '', Throwable $previous = null)
    {
        $this->attributeCode = $attributeCode;
        parent::__construct($message, 0, $previous);
    }

    public function getAttributeCode(): string
    {
        return $this->attributeCode;
    }

    public function setAttributeCode(string $attributeCode): void
    {
        $this->attributeCode = $attributeCode;
    }


}
