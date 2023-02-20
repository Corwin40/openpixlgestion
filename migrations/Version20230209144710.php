<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230209144710 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE statut ADD fiche_service_id INT DEFAULT NULL, DROP price, DROP hours');
        $this->addSql('ALTER TABLE statut ADD CONSTRAINT FK_E564F0BF430AAD11 FOREIGN KEY (fiche_service_id) REFERENCES fiche_service (id)');
        $this->addSql('CREATE INDEX IDX_E564F0BF430AAD11 ON statut (fiche_service_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE statut DROP FOREIGN KEY FK_E564F0BF430AAD11');
        $this->addSql('DROP INDEX IDX_E564F0BF430AAD11 ON statut');
        $this->addSql('ALTER TABLE statut ADD price NUMERIC(6, 2) NOT NULL, ADD hours VARCHAR(20) NOT NULL, DROP fiche_service_id');
    }
}
