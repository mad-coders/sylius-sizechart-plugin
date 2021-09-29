<?php

/*
 * This file is part of package
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

namespace Tests\Madcoders\SyliusSizechartPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Madcoders\SyliusSizechartPlugin\Entity\SizeChartInterface;
use Sylius\Behat\Service\Resolver\CurrentPageResolverInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Tests\Madcoders\SyliusSizechartPlugin\Behat\Page\Admin\SizeChart\CreatePageInterface;
use Tests\Madcoders\SyliusSizechartPlugin\Behat\Page\Admin\SizeChart\IndexPageInterface;
use Tests\Madcoders\SyliusSizechartPlugin\Behat\Page\Admin\SizeChart\UpdatePageInterface;
use Webmozart\Assert\Assert;
use FriendsOfBehat\PageObjectExtension\Page\UnexpectedPageException;
use Behat\Mink\Exception\ElementNotFoundException;

class SizeChartContext implements Context
{
    /** @var IndexPageInterface */
    private $indexPage;

    /**  @var CreatePageInterface */
    private $createPage;

    /** @var UpdatePageInterface */
    private $updatePage;

    /** @var RepositoryInterface */
    private $sizeChartRepository;

    /** @var CurrentPageResolverInterface */
    private $currentPageResolver;

    /** @var SharedStorageInterface */
    private $sharedStorage;

    public function __construct(
        IndexPageInterface $indexPage,
        CreatePageInterface $createPage,
        UpdatePageInterface $updatePage,
        RepositoryInterface $sizeChartRepository,
        CurrentPageResolverInterface $currentPageResolver,
        SharedStorageInterface $sharedStorage
    )
    {
        $this->indexPage = $indexPage;
        $this->createPage = $createPage;
        $this->updatePage = $updatePage;
        $this->sizeChartRepository = $sizeChartRepository;
        $this->currentPageResolver = $currentPageResolver;
        $this->sharedStorage = $sharedStorage;
    }

    /**
     * @Given I am on size chart index page
     *
     * @throws UnexpectedPageException
     */
    public function iAmOnSizeChartIndexPage(): void
    {
        $this->indexPage->open();
    }

    /**
     * @Given I want to create a new size chart
     * @Given I want to add a new size chart
     * @When I click create button
     *
     * @throws UnexpectedPageException
     */
    public function iCreateNewSizeChart(): void
    {
        $this->createPage->open();
    }

    /**
     * @Given I am on size chart edit page for size chart code :sizeChartCode
     *
     * @param string $sizeChartCode
     *
     * @throws UnexpectedPageException
     */
    public function imOnSizeChartEditPageByCode(string $sizeChartCode): void
    {
        $sizeChartId = $this->findSizeChartIdByCode($sizeChartCode);
        $this->updatePage->open(['id' => $sizeChartId]);
    }

    /**
     * @When I should be redirected to size chart create page
     *
     * @throws UnexpectedPageException
     */
    public function iShouldBeOnSizeChartCreatePage(): void
    {
        $this->createPage->verify();
    }

    /**
     * @When I fill create form with following data:
     *
     * @param TableNode $table
     *
     * @throws ElementNotFoundException
     */
    public function iFillCreateForm(TableNode $table): void
    {
        $formName = 'size_chart';
        $localeCode = $this->getAdminLocaleCode();
        foreach($table as $row) {
            $translationPrefix = $row['type'] === 'translations' ? 'translations_'. $localeCode . '_' : '';
            $locator = sprintf('%s_%s%s', $formName, $translationPrefix, $row['field']);

            $this->createPage->choosesFormElement($row['value'], $locator);
        }
    }

    /**
     * @When I enable it
     * @When I switch on enable toggle
     */
    public function iEnableIt(): void
    {
        $this->createPage->enable();
    }

    /**
     * @When I switch on :code attribute criteria option
     */
    public function iSwitchAttributeCriteriaOption(string $code): void
    {
        $this->createPage->enableCriteriaOption($code);
    }

    /**
     * @When I select :valueName for :attributeCode attribute criteria
     *
     * @param string $valueName
     * @param string $attributeCode
     *
     * @throws ElementNotFoundException
     */
    public function iSelectValueAttributeCriteria(string $valueName, string $attributeCode): void
    {
        $locator = 'size_chart[criteria][attribute'.$attributeCode.']';

        $this->createPage->choosesFormElement($valueName, $locator);
    }

    /**
     * @When I attach the :path file in :language
     * @When I attach the :path size chart
     * @When I replace size chart file with :path in :language
     */
    public function iAttachSizeChartFile(string $path, string $language = 'en_US'): void
    {
        $currentPage = $this->resolveCurrentPage();

        $currentPage->attachFile($path, $language);
    }

    /**
     * @When I change size chart name to :newSizeChartName in :language
     *
     * @param string $newSizeChartName
     * @param string|null $language
     *
     * @throws ElementNotFoundException
     */
    public function iChangeSizeChartName(string $newSizeChartName, string $language = 'en_US'): void
    {
        $locator = 'size_chart_translations_'. $language . '_name';

        $this->createPage->choosesFormElement($newSizeChartName, $locator);
    }

    /**
     * @When I click submit button
     *
     * @throws ElementNotFoundException
     */
    public function iClickSubmitButton(): void
    {
        $this->createPage->create();
    }

    private function getAdminLocaleCode(): ?string
    {
        /** @var AdminUserInterface $adminUser */
        $adminUser = $this->sharedStorage->get('administrator');

        return $adminUser->getLocaleCode();
    }

    /**
     * @When I click save changes button
     */
    public function iClickSaveChangesButton(): void
    {
        $this->updatePage->saveChanges();
    }

    private function findSizeChartIdByCode(string $code): ?int
    {
        /** @var SizeChartInterface $sizeChart */
        $sizeChart = $this->sizeChartRepository->findOneBy(['code' => $code]);
        Assert::notNull($sizeChart->getId());

        return $sizeChart->getId();
    }

    /**
     * @return CreatePageInterface|UpdatePageInterface
     */
    private function resolveCurrentPage()
    {
        return $this->currentPageResolver->getCurrentPageWithForm([
            $this->createPage,
            $this->updatePage
        ]);
    }
}
