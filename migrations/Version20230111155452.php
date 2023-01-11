<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230111155452 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client_service DROP FOREIGN KEY FK_B3A0DEAF19EB6921');
        $this->addSql('ALTER TABLE client_service DROP FOREIGN KEY FK_B3A0DEAFED5CA9E6');
        $this->addSql('ALTER TABLE service_statut DROP FOREIGN KEY FK_DBA3F589F6203804');
        $this->addSql('ALTER TABLE service_statut DROP FOREIGN KEY FK_DBA3F589ED5CA9E6');
        $this->addSql('DROP TABLE client_service');
        $this->addSql('DROP TABLE service_statut');
        $this->addSql('ALTER TABLE client CHANGE created_at created_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE service DROP email, DROP password, CHANGE created_at created_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE statut CHANGE created_at created_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client_service (client_id INT NOT NULL, service_id INT NOT NULL, INDEX IDX_B3A0DEAFED5CA9E6 (service_id), INDEX IDX_B3A0DEAF19EB6921 (client_id), PRIMARY KEY(client_id, service_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE service_statut (service_id INT NOT NULL, statut_id INT NOT NULL, INDEX IDX_DBA3F589ED5CA9E6 (service_id), INDEX IDX_DBA3F589F6203804 (statut_id), PRIMARY KEY(service_id, statut_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE client_service ADD CONSTRAINT FK_B3A0DEAF19EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client_service ADD CONSTRAINT FK_B3A0DEAFED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE service_statut ADD CONSTRAINT FK_DBA3F589F6203804 FOREIGN KEY (statut_id) REFERENCES statut (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE service_statut ADD CONSTRAINT FK_DBA3F589ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client CHANGE created_at created_at DATE NOT NULL, CHANGE updated_at updated_at DATE NOT NULL');
        $this->addSql('ALTER TABLE service ADD email VARCHAR(50) NOT NULL, ADD password VARCHAR(50) NOT NULL, CHANGE created_at created_at DATE NOT NULL, CHANGE updated_at updated_at DATE NOT NULL');
        $this->addSql('ALTER TABLE statut CHANGE created_at created_at DATE NOT NULL, CHANGE updated_at updated_at DATE NOT NULL');
    }
}
