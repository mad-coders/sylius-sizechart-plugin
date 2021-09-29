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

namespace Tests\Madcoders\SyliusSizechartPlugin\Behat\Page\Admin\SizeChart;

use Sylius\Behat\Page\Admin\Crud\UpdatePage as BaseUpdatePage;
use Tests\Madcoders\SyliusSizechartPlugin\Behat\Behaviour\ChoosesFormElement;

class UpdatePage extends BaseUpdatePage implements UpdatePageInterface
{
    use ChoosesFormElement;

    public function attachFile(string $path, string $localeCode): void
    {
        $filesPath = $this->getParameter('files_path');
        $translationPrefix = '#size_chart_translations_'. $localeCode . '_file';

        $this->getDocument()->find('css', $translationPrefix)->attachFile($filesPath . $path);
    }
}
