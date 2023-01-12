<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230112152849 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE service_client (service_id INT NOT NULL, client_id INT NOT NULL, INDEX IDX_F9830163ED5CA9E6 (service_id), INDEX IDX_F983016319EB6921 (client_id), PRIMARY KEY(service_id, client_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE service_client ADD CONSTRAINT FK_F9830163ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE service_client ADD CONSTRAINT FK_F983016319EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service_client DROP FOREIGN KEY FK_F9830163ED5CA9E6');
        $this->addSql('ALTER TABLE service_client DROP FOREIGN KEY FK_F983016319EB6921');
        $this->addSql('DROP TABLE service_client');
    }
}
