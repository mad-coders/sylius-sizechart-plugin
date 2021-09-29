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

namespace Tests\Madcoders\SyliusSizechartPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Tests\Madcoders\SyliusSizechartPlugin\Behat\Page\Shop\CreditsPageInterface;
use Webmozart\Assert\Assert;

final class CreditsContext implements Context
{
    /**
     * @var CreditsPageInterface
     */
    private $creditsPage;

    /**
     * @param CreditsPageInterface $creditsPage
     */
    public function __construct(CreditsPageInterface $creditsPage)
    {
        $this->creditsPage = $creditsPage;
    }

    /**
     * @When a customer visits credits page
     */
    public function customerVisitsCreditsPage(): void
    {
        $this->creditsPage->open();
    }

    /**
     * @Then they should see credits header :header
     */
    public function theyShouldBeDynamicallyGreetedWithGreeting(string $header): void
    {
        Assert::same($this->creditsPage->getHeader(), $header);
    }
}
