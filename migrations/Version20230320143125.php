<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230320143125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE typo_serv (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(25) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE service ADD typo_serv_id INT DEFAULT NULL, ADD duration TIME DEFAULT NULL, ADD is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2F19C3E87 FOREIGN KEY (typo_serv_id) REFERENCES typo_serv (id)');
        $this->addSql('CREATE INDEX IDX_E19D9AD2F19C3E87 ON service (typo_serv_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2F19C3E87');
        $this->addSql('DROP TABLE typo_serv');
        $this->addSql('DROP INDEX IDX_E19D9AD2F19C3E87 ON service');
        $this->addSql('ALTER TABLE service DROP typo_serv_id, DROP duration, DROP is_active');
    }
}
