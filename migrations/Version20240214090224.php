<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240214090224 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice ADD total NUMERIC(10, 0) NOT NULL, ADD tva NUMERIC(10, 0) NOT NULL');
        $this->addSql('ALTER TABLE invoice_item ADD total_hour INT NOT NULL, ADD montant_ht NUMERIC(10, 0) NOT NULL, ADD montant_ttc NUMERIC(10, 0) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice DROP total, DROP tva');
        $this->addSql('ALTER TABLE invoice_item DROP total_hour, DROP montant_ht, DROP montant_ttc');
    }
}
