<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250408175531 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE historique (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(255) NOT NULL, salle VARCHAR(255) NOT NULL, date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE abonnement DROP FOREIGN KEY abonnement_ibfk_1');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY commandes_ibfk_2');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY commandes_ibfk_1');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY fk_post');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY fk_user_id2');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY fk_user_id');
        $this->addSql('ALTER TABLE promotion DROP FOREIGN KEY promotion_ibfk_1');
        $this->addSql('DROP TABLE abonnement');
        $this->addSql('DROP TABLE commandes');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE produits');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE salledesport');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX idx_user ON equipement');
        $this->addSql('ALTER TABLE equipement DROP date_ajout, CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE id_salle id_salle INT NOT NULL, CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE fonctionnement fonctionnement TINYINT(1) NOT NULL, CHANGE prochaine_verification prochaine_verification DATE NOT NULL, CHANGE derniere_verification derniere_verification DATE NOT NULL, CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE equipement ADD CONSTRAINT FK_B8B4C6F3A0123F6C FOREIGN KEY (id_salle) REFERENCES salle_de_sport (id)');
        $this->addSql('DROP INDEX idx_salle ON equipement');
        $this->addSql('CREATE INDEX IDX_B8B4C6F3A0123F6C ON equipement (id_salle)');
        $this->addSql('DROP INDEX id_user ON exercice');
        $this->addSql('ALTER TABLE exercice CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE description description VARCHAR(255) NOT NULL, CHANGE image image VARCHAR(255) NOT NULL, CHANGE id_user id_user INT NOT NULL, CHANGE id_equipement id_equipement INT NOT NULL, CHANGE nom_exercice nom_exercice VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE exercice ADD CONSTRAINT FK_E418C74D1D3E4624 FOREIGN KEY (id_equipement) REFERENCES equipement (id)');
        $this->addSql('DROP INDEX id_equipement ON exercice');
        $this->addSql('CREATE INDEX IDX_E418C74D1D3E4624 ON exercice (id_equipement)');
        $this->addSql('DROP INDEX id_user ON salle_de_sport');
        $this->addSql('ALTER TABLE salle_de_sport CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE id_user id_user INT NOT NULL, CHANGE image image VARCHAR(255) NOT NULL, CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE zone zone VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonnement (AbonnementID INT AUTO_INCREMENT NOT NULL, SalleID INT NOT NULL, Nom VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, Description TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, Duree INT NOT NULL, Prix NUMERIC(10, 2) NOT NULL, DateCreation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, SalleName VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, INDEX SalleID (SalleID), PRIMARY KEY(AbonnementID)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE commandes (id_commande INT AUTO_INCREMENT NOT NULL, id_produit INT NOT NULL, id_user BIGINT NOT NULL, nom_client VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, adresse_livraison TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, telephone VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, quantite INT NOT NULL, date_commande DATETIME NOT NULL, statut_commande VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX id_produit (id_produit), INDEX id_user (id_user), PRIMARY KEY(id_commande)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, comment VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date DATE NOT NULL, likes INT NOT NULL, idPost INT DEFAULT NULL, idUser BIGINT NOT NULL, INDEX idUser (idUser), INDEX fk_post (idPost), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE post (idp INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, dateU DATE DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL, image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, idUser BIGINT DEFAULT NULL, UNIQUE INDEX fk_user_id (idUser), PRIMARY KEY(idp)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE produits (id_produit INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, categorie VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, prix NUMERIC(10, 2) NOT NULL, quantite_stock INT NOT NULL, disponible TINYINT(1) DEFAULT 1, image_path VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id_produit)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE promotion (PromotionID INT AUTO_INCREMENT NOT NULL, CodePromo VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Description TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, TypeReduction VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, ValeurReduction NUMERIC(10, 2) NOT NULL, DateDebut DATE NOT NULL, DateFin DATE NOT NULL, AbonnementID INT DEFAULT NULL, DateCreation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, SalleID INT DEFAULT NULL, INDEX promotion_ibfk_1 (AbonnementID), UNIQUE INDEX CodePromo (CodePromo), PRIMARY KEY(PromotionID)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE salledesport (SalleID INT AUTO_INCREMENT NOT NULL, Nom VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Adresse VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, Ville VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, CodePostal VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, Telephone VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, Email VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, DateCreation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(SalleID)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user (id BIGINT AUTO_INCREMENT NOT NULL, first_name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, last_name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, password_hash VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, phone_number VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, role VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, subscription_end_date DATE DEFAULT NULL, is_active TINYINT(1) DEFAULT 1, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, violation_count INT DEFAULT 0, location VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, cin VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, age INT NOT NULL, UNIQUE INDEX email (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE abonnement ADD CONSTRAINT abonnement_ibfk_1 FOREIGN KEY (SalleID) REFERENCES salledesport (SalleID)');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT commandes_ibfk_2 FOREIGN KEY (id_user) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT commandes_ibfk_1 FOREIGN KEY (id_produit) REFERENCES produits (id_produit) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT fk_post FOREIGN KEY (idPost) REFERENCES post (idp) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT fk_user_id2 FOREIGN KEY (idUser) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT fk_user_id FOREIGN KEY (idUser) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT promotion_ibfk_1 FOREIGN KEY (AbonnementID) REFERENCES abonnement (AbonnementID) ON DELETE CASCADE');
        $this->addSql('DROP TABLE historique');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE equipement DROP FOREIGN KEY FK_B8B4C6F3A0123F6C');
        $this->addSql('ALTER TABLE equipement DROP FOREIGN KEY FK_B8B4C6F3A0123F6C');
        $this->addSql('ALTER TABLE equipement ADD date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE id id BIGINT AUTO_INCREMENT NOT NULL, CHANGE id_salle id_salle BIGINT NOT NULL, CHANGE id_user id_user BIGINT DEFAULT NULL, CHANGE nom nom VARCHAR(100) NOT NULL, CHANGE fonctionnement fonctionnement TINYINT(1) DEFAULT 1 NOT NULL, CHANGE prochaine_verification prochaine_verification DATE DEFAULT NULL, CHANGE derniere_verification derniere_verification DATE DEFAULT NULL');
        $this->addSql('CREATE INDEX idx_user ON equipement (id_user)');
        $this->addSql('DROP INDEX idx_b8b4c6f3a0123f6c ON equipement');
        $this->addSql('CREATE INDEX idx_salle ON equipement (id_salle)');
        $this->addSql('ALTER TABLE equipement ADD CONSTRAINT FK_B8B4C6F3A0123F6C FOREIGN KEY (id_salle) REFERENCES salle_de_sport (id)');
        $this->addSql('ALTER TABLE exercice DROP FOREIGN KEY FK_E418C74D1D3E4624');
        $this->addSql('ALTER TABLE exercice DROP FOREIGN KEY FK_E418C74D1D3E4624');
        $this->addSql('ALTER TABLE exercice CHANGE id id BIGINT AUTO_INCREMENT NOT NULL, CHANGE id_equipement id_equipement BIGINT NOT NULL, CHANGE description description TEXT NOT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL, CHANGE id_user id_user BIGINT NOT NULL, CHANGE nom_exercice nom_exercice VARCHAR(100) NOT NULL');
        $this->addSql('CREATE INDEX id_user ON exercice (id_user)');
        $this->addSql('DROP INDEX idx_e418c74d1d3e4624 ON exercice');
        $this->addSql('CREATE INDEX id_equipement ON exercice (id_equipement)');
        $this->addSql('ALTER TABLE exercice ADD CONSTRAINT FK_E418C74D1D3E4624 FOREIGN KEY (id_equipement) REFERENCES equipement (id)');
        $this->addSql('ALTER TABLE salle_de_sport CHANGE id id BIGINT AUTO_INCREMENT NOT NULL, CHANGE nom nom VARCHAR(100) NOT NULL, CHANGE zone zone VARCHAR(100) NOT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL, CHANGE id_user id_user BIGINT NOT NULL');
        $this->addSql('CREATE INDEX id_user ON salle_de_sport (id_user)');
    }
}
