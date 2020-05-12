<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200512214233 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category CHANGE url_icon url_icon VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD unit VARCHAR(255) DEFAULT NULL, ADD price DOUBLE PRECISION DEFAULT NULL, CHANGE enabled_since enabled_since DATETIME NOT NULL');
        $this->addSql('ALTER TABLE season ADD url_icon VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category CHANGE url_icon url_icon VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE product DROP unit, DROP price, CHANGE enabled_since enabled_since DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE season DROP url_icon');
    }
}
