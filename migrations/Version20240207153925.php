<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240207153925 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice_item ADD ref_invoice_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice_item ADD CONSTRAINT FK_1DDE477B2F4C1EE3 FOREIGN KEY (ref_invoice_id) REFERENCES invoice (id)');
        $this->addSql('CREATE INDEX IDX_1DDE477B2F4C1EE3 ON invoice_item (ref_invoice_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice_item DROP FOREIGN KEY FK_1DDE477B2F4C1EE3');
        $this->addSql('DROP INDEX IDX_1DDE477B2F4C1EE3 ON invoice_item');
        $this->addSql('ALTER TABLE invoice_item DROP ref_invoice_id');
    }
}
