<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250914130427 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bien_immobilier (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, departement_id INT DEFAULT NULL, proprietaire_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, surface DOUBLE PRECISION DEFAULT NULL, nbre_chambres INT DEFAULT NULL, adresse VARCHAR(255) NOT NULL, statut VARCHAR(255) NOT NULL, offre_type VARCHAR(50) NOT NULL, mantant NUMERIC(10, 2) NOT NULL, INDEX IDX_D1BE34E1C54C8C93 (type_id), INDEX IDX_D1BE34E1CCF9E01E (departement_id), INDEX IDX_D1BE34E176C50E4A (proprietaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, profession VARCHAR(255) DEFAULT NULL, passeport VARCHAR(50) DEFAULT NULL, UNIQUE INDEX UNIQ_C7440455FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE departement (id INT AUTO_INCREMENT NOT NULL, ville_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_C1765B63A73F0036 (ville_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etat_lieu (id INT AUTO_INCREMENT NOT NULL, operation_id INT NOT NULL, date_etat DATE NOT NULL, type VARCHAR(50) NOT NULL, commentaire LONGTEXT DEFAULT NULL, INDEX IDX_D1C5BDF644AC3583 (operation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, operation_id INT NOT NULL, date_facture DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', montant_total NUMERIC(10, 2) NOT NULL, commission_agence NUMERIC(10, 2) DEFAULT NULL, details LONGTEXT DEFAULT NULL, fichier_facture VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_FE86641044AC3583 (operation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE operation_bien (id INT AUTO_INCREMENT NOT NULL, bien_id INT NOT NULL, acheteur_id INT DEFAULT NULL, vendeur_id INT DEFAULT NULL, locataire_id INT DEFAULT NULL, bailleur_id INT DEFAULT NULL, type VARCHAR(20) NOT NULL, date_debut DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_fin DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_operation DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', montant NUMERIC(10, 2) NOT NULL, caution NUMERIC(10, 2) DEFAULT NULL, statut VARCHAR(20) NOT NULL, commentaire LONGTEXT DEFAULT NULL, INDEX IDX_6A9D9E17BD95B80F (bien_id), INDEX IDX_6A9D9E1796A7BB5F (acheteur_id), INDEX IDX_6A9D9E17858C065E (vendeur_id), INDEX IDX_6A9D9E17D8A38199 (locataire_id), INDEX IDX_6A9D9E1757B5D0A2 (bailleur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE piece_jointe (id INT AUTO_INCREMENT NOT NULL, bien_id INT DEFAULT NULL, etat_lieu_id INT DEFAULT NULL, url_fichier VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, type VARCHAR(50) NOT NULL, INDEX IDX_AB5111D4BD95B80F (bien_id), INDEX IDX_AB5111D46D6D2C49 (etat_lieu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proprietaire (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, profession VARCHAR(255) DEFAULT NULL, nom_banque VARCHAR(255) DEFAULT NULL, adresse_banque VARCHAR(255) DEFAULT NULL, rib VARCHAR(34) DEFAULT NULL, iban VARCHAR(34) DEFAULT NULL, UNIQUE INDEX UNIQ_69E399D6FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_bien (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(50) NOT NULL, cin VARCHAR(50) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, telephone VARCHAR(50) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, date_naissance DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ville (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE visite (id INT AUTO_INCREMENT NOT NULL, bien_id INT NOT NULL, relation_id INT NOT NULL, date_programmee DATETIME NOT NULL, date_reelle DATETIME NOT NULL, statut VARCHAR(50) NOT NULL, commentaire LONGTEXT DEFAULT NULL, INDEX IDX_B09C8CBBBD95B80F (bien_id), INDEX IDX_B09C8CBB3256915B (relation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE visiteur (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, passeport VARCHAR(50) DEFAULT NULL, budget_max NUMERIC(10, 2) DEFAULT NULL, profession VARCHAR(255) DEFAULT NULL, preference VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_4EA587B8FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bien_immobilier ADD CONSTRAINT FK_D1BE34E1C54C8C93 FOREIGN KEY (type_id) REFERENCES type_bien (id)');
        $this->addSql('ALTER TABLE bien_immobilier ADD CONSTRAINT FK_D1BE34E1CCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id)');
        $this->addSql('ALTER TABLE bien_immobilier ADD CONSTRAINT FK_D1BE34E176C50E4A FOREIGN KEY (proprietaire_id) REFERENCES proprietaire (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE departement ADD CONSTRAINT FK_C1765B63A73F0036 FOREIGN KEY (ville_id) REFERENCES ville (id)');
        $this->addSql('ALTER TABLE etat_lieu ADD CONSTRAINT FK_D1C5BDF644AC3583 FOREIGN KEY (operation_id) REFERENCES operation_bien (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE86641044AC3583 FOREIGN KEY (operation_id) REFERENCES operation_bien (id)');
        $this->addSql('ALTER TABLE operation_bien ADD CONSTRAINT FK_6A9D9E17BD95B80F FOREIGN KEY (bien_id) REFERENCES bien_immobilier (id)');
        $this->addSql('ALTER TABLE operation_bien ADD CONSTRAINT FK_6A9D9E1796A7BB5F FOREIGN KEY (acheteur_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE operation_bien ADD CONSTRAINT FK_6A9D9E17858C065E FOREIGN KEY (vendeur_id) REFERENCES proprietaire (id)');
        $this->addSql('ALTER TABLE operation_bien ADD CONSTRAINT FK_6A9D9E17D8A38199 FOREIGN KEY (locataire_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE operation_bien ADD CONSTRAINT FK_6A9D9E1757B5D0A2 FOREIGN KEY (bailleur_id) REFERENCES proprietaire (id)');
        $this->addSql('ALTER TABLE piece_jointe ADD CONSTRAINT FK_AB5111D4BD95B80F FOREIGN KEY (bien_id) REFERENCES bien_immobilier (id)');
        $this->addSql('ALTER TABLE piece_jointe ADD CONSTRAINT FK_AB5111D46D6D2C49 FOREIGN KEY (etat_lieu_id) REFERENCES etat_lieu (id)');
        $this->addSql('ALTER TABLE proprietaire ADD CONSTRAINT FK_69E399D6FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE visite ADD CONSTRAINT FK_B09C8CBBBD95B80F FOREIGN KEY (bien_id) REFERENCES bien_immobilier (id)');
        $this->addSql('ALTER TABLE visite ADD CONSTRAINT FK_B09C8CBB3256915B FOREIGN KEY (relation_id) REFERENCES visiteur (id)');
        $this->addSql('ALTER TABLE visiteur ADD CONSTRAINT FK_4EA587B8FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bien_immobilier DROP FOREIGN KEY FK_D1BE34E1C54C8C93');
        $this->addSql('ALTER TABLE bien_immobilier DROP FOREIGN KEY FK_D1BE34E1CCF9E01E');
        $this->addSql('ALTER TABLE bien_immobilier DROP FOREIGN KEY FK_D1BE34E176C50E4A');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455FB88E14F');
        $this->addSql('ALTER TABLE departement DROP FOREIGN KEY FK_C1765B63A73F0036');
        $this->addSql('ALTER TABLE etat_lieu DROP FOREIGN KEY FK_D1C5BDF644AC3583');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE86641044AC3583');
        $this->addSql('ALTER TABLE operation_bien DROP FOREIGN KEY FK_6A9D9E17BD95B80F');
        $this->addSql('ALTER TABLE operation_bien DROP FOREIGN KEY FK_6A9D9E1796A7BB5F');
        $this->addSql('ALTER TABLE operation_bien DROP FOREIGN KEY FK_6A9D9E17858C065E');
        $this->addSql('ALTER TABLE operation_bien DROP FOREIGN KEY FK_6A9D9E17D8A38199');
        $this->addSql('ALTER TABLE operation_bien DROP FOREIGN KEY FK_6A9D9E1757B5D0A2');
        $this->addSql('ALTER TABLE piece_jointe DROP FOREIGN KEY FK_AB5111D4BD95B80F');
        $this->addSql('ALTER TABLE piece_jointe DROP FOREIGN KEY FK_AB5111D46D6D2C49');
        $this->addSql('ALTER TABLE proprietaire DROP FOREIGN KEY FK_69E399D6FB88E14F');
        $this->addSql('ALTER TABLE visite DROP FOREIGN KEY FK_B09C8CBBBD95B80F');
        $this->addSql('ALTER TABLE visite DROP FOREIGN KEY FK_B09C8CBB3256915B');
        $this->addSql('ALTER TABLE visiteur DROP FOREIGN KEY FK_4EA587B8FB88E14F');
        $this->addSql('DROP TABLE bien_immobilier');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE departement');
        $this->addSql('DROP TABLE etat_lieu');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE operation_bien');
        $this->addSql('DROP TABLE piece_jointe');
        $this->addSql('DROP TABLE proprietaire');
        $this->addSql('DROP TABLE type_bien');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE ville');
        $this->addSql('DROP TABLE visite');
        $this->addSql('DROP TABLE visiteur');
    }
}
