<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250417122548 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE commandes (id_commande INT NOT NULL, id_produit INT DEFAULT NULL, id_user BIGINT DEFAULT NULL, nom_client VARCHAR(100) NOT NULL, adresse_livraison LONGTEXT NOT NULL, telephone VARCHAR(20) NOT NULL, quantite INT NOT NULL, date_commande DATETIME NOT NULL, statut_commande VARCHAR(20) NOT NULL, INDEX IDX_35D4282CF7384557 (id_produit), INDEX IDX_35D4282C6B3CA4B (id_user), PRIMARY KEY(id_commande)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE comment (id INT NOT NULL, comment VARCHAR(255) NOT NULL, date DATE NOT NULL, likes INT NOT NULL, idPost INT DEFAULT NULL, idUser BIGINT DEFAULT NULL, INDEX IDX_9474526C29773213 (idPost), INDEX IDX_9474526CFE6E88D7 (idUser), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE equipement (id INT AUTO_INCREMENT NOT NULL, id_salle INT NOT NULL, id_user INT NOT NULL, nom VARCHAR(255) NOT NULL, fonctionnement TINYINT(1) NOT NULL, prochaine_verification DATE NOT NULL, derniere_verification DATE NOT NULL, INDEX IDX_B8B4C6F3A0123F6C (id_salle), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE exercice (id INT AUTO_INCREMENT NOT NULL, id_equipement INT NOT NULL, description VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, id_user INT NOT NULL, nom_exercice VARCHAR(255) NOT NULL, INDEX IDX_E418C74D1D3E4624 (id_equipement), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE historique (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(255) NOT NULL, salle VARCHAR(255) NOT NULL, date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE post (idp INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, date_u DATE NOT NULL, image VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, idUser BIGINT DEFAULT NULL, INDEX IDX_5A8A6C8DFE6E88D7 (idUser), PRIMARY KEY(idp)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE produits (id_produit INT NOT NULL, nom VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, categorie VARCHAR(50) NOT NULL, prix DOUBLE PRECISION NOT NULL, quantite_stock INT NOT NULL, disponible TINYINT(1) NOT NULL, image_path VARCHAR(255) NOT NULL, PRIMARY KEY(id_produit)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE salle_de_sport (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, zone VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, id_user INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id BIGINT NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, email VARCHAR(255) NOT NULL, password_hash VARCHAR(255) NOT NULL, phone_number VARCHAR(20) NOT NULL, role VARCHAR(50) NOT NULL, subscription_end_date DATE NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, violation_count INT NOT NULL, location VARCHAR(255) NOT NULL, cin VARCHAR(20) NOT NULL, age INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commandes ADD CONSTRAINT FK_35D4282CF7384557 FOREIGN KEY (id_produit) REFERENCES produits (id_produit) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commandes ADD CONSTRAINT FK_35D4282C6B3CA4B FOREIGN KEY (id_user) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment ADD CONSTRAINT FK_9474526C29773213 FOREIGN KEY (idPost) REFERENCES post (idp) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment ADD CONSTRAINT FK_9474526CFE6E88D7 FOREIGN KEY (idUser) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE equipement ADD CONSTRAINT FK_B8B4C6F3A0123F6C FOREIGN KEY (id_salle) REFERENCES salle_de_sport (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exercice ADD CONSTRAINT FK_E418C74D1D3E4624 FOREIGN KEY (id_equipement) REFERENCES equipement (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DFE6E88D7 FOREIGN KEY (idUser) REFERENCES user (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282CF7384557
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282C6B3CA4B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment DROP FOREIGN KEY FK_9474526C29773213
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment DROP FOREIGN KEY FK_9474526CFE6E88D7
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE equipement DROP FOREIGN KEY FK_B8B4C6F3A0123F6C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exercice DROP FOREIGN KEY FK_E418C74D1D3E4624
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DFE6E88D7
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE commandes
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE comment
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE equipement
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE exercice
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE historique
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE post
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE produits
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE salle_de_sport
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
