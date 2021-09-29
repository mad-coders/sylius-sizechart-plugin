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

namespace Madcoders\SyliusSizechartPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210929071258 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Initialize size chart plugin tables';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE madcoders_size_chart (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(100) NOT NULL, criteria JSON NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_3486E81D77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE madcoders_size_chart_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, file_path VARCHAR(255) DEFAULT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_7D53B1F92C2AC5D3 (translatable_id), UNIQUE INDEX size_chart_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE madcoders_size_chart_translation ADD CONSTRAINT FK_7D53B1F92C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES madcoders_size_chart (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE madcoders_size_chart_translation DROP FOREIGN KEY FK_7D53B1F92C2AC5D3');
        $this->addSql('DROP TABLE madcoders_size_chart');
        $this->addSql('DROP TABLE madcoders_size_chart_translation');
    }
}
