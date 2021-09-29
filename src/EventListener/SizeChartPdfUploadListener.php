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

namespace Madcoders\SyliusSizechartPlugin\EventListener;

use Madcoders\SyliusSizechartPlugin\Entity\SizeChartPdfAwareInterface;
use Madcoders\SyliusSizechartPlugin\Uploader\SizeChartPdfUploaderInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

final class SizeChartPdfUploadListener
{
    /** @var SizeChartPdfUploaderInterface */
    private $uploader;

    public function __construct(SizeChartPdfUploaderInterface $uploader)
    {
        $this->uploader = $uploader;
    }

    public function uploadSizeChartPdf(GenericEvent $event): void
    {
        $subject = $event->getSubject();

        if ($subject instanceof SizeChartPdfAwareInterface) {
            if (null !== $subject->getFile()) {
                $this->uploader->upload($subject);
            }
        }

        if ($subject instanceof TranslatableInterface) {
            foreach($subject->getTranslations() as $translation) {
                if(!$translation instanceof SizeChartPdfAwareInterface) {
                    continue;
                }

                if (null !== $translation->getFile()) {
                    $this->uploader->upload($translation);
                }
            }
        }
    }
}
