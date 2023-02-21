<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230221105820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE statut ADD author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE statut ADD CONSTRAINT FK_E564F0BFF675F31B FOREIGN KEY (author_id) REFERENCES `member` (id)');
        $this->addSql('CREATE INDEX IDX_E564F0BFF675F31B ON statut (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE statut DROP FOREIGN KEY FK_E564F0BFF675F31B');
        $this->addSql('DROP INDEX IDX_E564F0BFF675F31B ON statut');
        $this->addSql('ALTER TABLE statut DROP author_id');
    }
}
