<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240416142906 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fiche_service CHANGE price_hour price_hour INT DEFAULT NULL, CHANGE price_bundle price_bundle INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice CHANGE descriptif descriptif LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fiche_service CHANGE price_hour price_hour INT NOT NULL, CHANGE price_bundle price_bundle INT NOT NULL');
        $this->addSql('ALTER TABLE invoice CHANGE descriptif descriptif LONGTEXT NOT NULL');
    }
}
