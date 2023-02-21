<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230221120012 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE intervention (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, ficheservice_id INT DEFAULT NULL, name VARCHAR(100) DEFAULT NULL, desctiption LONGTEXT DEFAULT NULL, started_at TIME DEFAULT NULL, finished_at TIME DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D11814ABF675F31B (author_id), INDEX IDX_D11814AB27AEB513 (ficheservice_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE intervention ADD CONSTRAINT FK_D11814ABF675F31B FOREIGN KEY (author_id) REFERENCES `member` (id)');
        $this->addSql('ALTER TABLE intervention ADD CONSTRAINT FK_D11814AB27AEB513 FOREIGN KEY (ficheservice_id) REFERENCES fiche_service (id)');
        $this->addSql('ALTER TABLE member_statut DROP FOREIGN KEY FK_C1B2728C7597D3FE');
        $this->addSql('ALTER TABLE member_statut DROP FOREIGN KEY FK_C1B2728CF6203804');
        $this->addSql('ALTER TABLE statut DROP FOREIGN KEY FK_E564F0BF430AAD11');
        $this->addSql('DROP TABLE member_statut');
        $this->addSql('DROP TABLE statut');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE member_statut (member_id INT NOT NULL, statut_id INT NOT NULL, INDEX IDX_C1B2728C7597D3FE (member_id), INDEX IDX_C1B2728CF6203804 (statut_id), PRIMARY KEY(member_id, statut_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE statut (id INT AUTO_INCREMENT NOT NULL, fiche_service_id INT DEFAULT NULL, statut VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATE NOT NULL, updated_at DATE NOT NULL, echeance DATE NOT NULL, name VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, notes LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, started_at TIME DEFAULT NULL, finished_at TIME DEFAULT NULL, time SMALLINT DEFAULT NULL, price INT DEFAULT NULL, INDEX IDX_E564F0BF430AAD11 (fiche_service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE member_statut ADD CONSTRAINT FK_C1B2728C7597D3FE FOREIGN KEY (member_id) REFERENCES `member` (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE member_statut ADD CONSTRAINT FK_C1B2728CF6203804 FOREIGN KEY (statut_id) REFERENCES statut (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE statut ADD CONSTRAINT FK_E564F0BF430AAD11 FOREIGN KEY (fiche_service_id) REFERENCES fiche_service (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE intervention DROP FOREIGN KEY FK_D11814ABF675F31B');
        $this->addSql('ALTER TABLE intervention DROP FOREIGN KEY FK_D11814AB27AEB513');
        $this->addSql('DROP TABLE intervention');
    }
}
