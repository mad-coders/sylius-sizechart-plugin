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

namespace Madcoders\SyliusSizechartPlugin\Ui\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

class AdminMenuListener
{
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $newRmaSubmenu = $menu
            ->addChild('size-charts')
            ->setLabel('Madcoders Size Charts')
        ;

        $newRmaSubmenu
            ->addChild('size-charts-list', ['route' => 'madcoders_sizechart_admin_size_chart_index'])
            ->setLabel('Size Charts List')
            ->setLabelAttribute('icon', 'crop')
        ;
    }
}
