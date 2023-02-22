<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230221133350 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, members_id INT NOT NULL, typeclient_id INT NOT NULL, name VARCHAR(50) NOT NULL, surname VARCHAR(50) NOT NULL, address VARCHAR(50) NOT NULL, city VARCHAR(50) NOT NULL, postal_code VARCHAR(5) NOT NULL, phone VARCHAR(14) NOT NULL, email VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_C7440455BD01F5ED (members_id), INDEX IDX_C7440455FAD40BBD (typeclient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fiche_service (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, client_id INT DEFAULT NULL, service_id INT DEFAULT NULL, statut VARCHAR(255) DEFAULT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, echeance DATE NOT NULL, time SMALLINT DEFAULT NULL, price INT DEFAULT NULL, INDEX IDX_F99CC108F675F31B (author_id), INDEX IDX_F99CC10819EB6921 (client_id), INDEX IDX_F99CC108ED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intervention (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, ficheservice_id INT DEFAULT NULL, name VARCHAR(100) DEFAULT NULL, description LONGTEXT DEFAULT NULL, started_at TIME DEFAULT NULL, finished_at TIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_D11814ABF675F31B (author_id), INDEX IDX_D11814AB27AEB513 (ficheservice_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `member` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_70E4FA78E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, members_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, archives VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_E19D9AD2BD01F5ED (members_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_client (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455BD01F5ED FOREIGN KEY (members_id) REFERENCES `member` (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455FAD40BBD FOREIGN KEY (typeclient_id) REFERENCES type_client (id)');
        $this->addSql('ALTER TABLE fiche_service ADD CONSTRAINT FK_F99CC108F675F31B FOREIGN KEY (author_id) REFERENCES `member` (id)');
        $this->addSql('ALTER TABLE fiche_service ADD CONSTRAINT FK_F99CC10819EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE fiche_service ADD CONSTRAINT FK_F99CC108ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE intervention ADD CONSTRAINT FK_D11814ABF675F31B FOREIGN KEY (author_id) REFERENCES `member` (id)');
        $this->addSql('ALTER TABLE intervention ADD CONSTRAINT FK_D11814AB27AEB513 FOREIGN KEY (ficheservice_id) REFERENCES fiche_service (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2BD01F5ED FOREIGN KEY (members_id) REFERENCES `member` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455BD01F5ED');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455FAD40BBD');
        $this->addSql('ALTER TABLE fiche_service DROP FOREIGN KEY FK_F99CC108F675F31B');
        $this->addSql('ALTER TABLE fiche_service DROP FOREIGN KEY FK_F99CC10819EB6921');
        $this->addSql('ALTER TABLE fiche_service DROP FOREIGN KEY FK_F99CC108ED5CA9E6');
        $this->addSql('ALTER TABLE intervention DROP FOREIGN KEY FK_D11814ABF675F31B');
        $this->addSql('ALTER TABLE intervention DROP FOREIGN KEY FK_D11814AB27AEB513');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2BD01F5ED');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE fiche_service');
        $this->addSql('DROP TABLE intervention');
        $this->addSql('DROP TABLE `member`');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE type_client');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
