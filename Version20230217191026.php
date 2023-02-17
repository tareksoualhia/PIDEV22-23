<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230217191026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonnement (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, formation_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_64C19C15200282E (formation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entraineur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipe (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement_sponsor (evenement_id INT NOT NULL, sponsor_id INT NOT NULL, INDEX IDX_8289DE08FD02F13 (evenement_id), INDEX IDX_8289DE0812F7FB51 (sponsor_id), PRIMARY KEY(evenement_id, sponsor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement_solo (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation_abonnement (formation_id INT NOT NULL, abonnement_id INT NOT NULL, INDEX IDX_CDBABB825200282E (formation_id), INDEX IDX_CDBABB82F1D74413 (abonnement_id), PRIMARY KEY(formation_id, abonnement_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE joueur (id INT AUTO_INCREMENT NOT NULL, equipe_id INT NOT NULL, abonnement_id INT NOT NULL, evenement_solo_id INT NOT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_FD71A9C56D861B89 (equipe_id), INDEX IDX_FD71A9C5F1D74413 (abonnement_id), INDEX IDX_FD71A9C51CCCDEA0 (evenement_solo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, joueur_id INT NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_CE606404A9E2D76C (joueur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, joueur_id INT NOT NULL, abonnement_id INT NOT NULL, entraineur_id INT DEFAULT NULL, prix VARCHAR(255) NOT NULL, INDEX IDX_42C84955A9E2D76C (joueur_id), INDEX IDX_42C84955F1D74413 (abonnement_id), INDEX IDX_42C84955F8478A1 (entraineur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sponsor (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C15200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
        $this->addSql('ALTER TABLE evenement_sponsor ADD CONSTRAINT FK_8289DE08FD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evenement_sponsor ADD CONSTRAINT FK_8289DE0812F7FB51 FOREIGN KEY (sponsor_id) REFERENCES sponsor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formation_abonnement ADD CONSTRAINT FK_CDBABB825200282E FOREIGN KEY (formation_id) REFERENCES formation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formation_abonnement ADD CONSTRAINT FK_CDBABB82F1D74413 FOREIGN KEY (abonnement_id) REFERENCES abonnement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE joueur ADD CONSTRAINT FK_FD71A9C56D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id)');
        $this->addSql('ALTER TABLE joueur ADD CONSTRAINT FK_FD71A9C5F1D74413 FOREIGN KEY (abonnement_id) REFERENCES abonnement (id)');
        $this->addSql('ALTER TABLE joueur ADD CONSTRAINT FK_FD71A9C51CCCDEA0 FOREIGN KEY (evenement_solo_id) REFERENCES evenement_solo (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404A9E2D76C FOREIGN KEY (joueur_id) REFERENCES joueur (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A9E2D76C FOREIGN KEY (joueur_id) REFERENCES joueur (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955F1D74413 FOREIGN KEY (abonnement_id) REFERENCES abonnement (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955F8478A1 FOREIGN KEY (entraineur_id) REFERENCES entraineur (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C15200282E');
        $this->addSql('ALTER TABLE evenement_sponsor DROP FOREIGN KEY FK_8289DE08FD02F13');
        $this->addSql('ALTER TABLE evenement_sponsor DROP FOREIGN KEY FK_8289DE0812F7FB51');
        $this->addSql('ALTER TABLE formation_abonnement DROP FOREIGN KEY FK_CDBABB825200282E');
        $this->addSql('ALTER TABLE formation_abonnement DROP FOREIGN KEY FK_CDBABB82F1D74413');
        $this->addSql('ALTER TABLE joueur DROP FOREIGN KEY FK_FD71A9C56D861B89');
        $this->addSql('ALTER TABLE joueur DROP FOREIGN KEY FK_FD71A9C5F1D74413');
        $this->addSql('ALTER TABLE joueur DROP FOREIGN KEY FK_FD71A9C51CCCDEA0');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404A9E2D76C');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A9E2D76C');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955F1D74413');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955F8478A1');
        $this->addSql('DROP TABLE abonnement');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE entraineur');
        $this->addSql('DROP TABLE equipe');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE evenement_sponsor');
        $this->addSql('DROP TABLE evenement_solo');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE formation_abonnement');
        $this->addSql('DROP TABLE joueur');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE sponsor');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
