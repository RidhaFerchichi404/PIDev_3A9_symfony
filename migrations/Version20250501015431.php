<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250501015431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password_hash VARCHAR(255) NOT NULL, phone_number VARCHAR(20) DEFAULT NULL, role VARCHAR(50) NOT NULL, subscription_end_date DATE NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, violation_count INT NOT NULL, location VARCHAR(255) DEFAULT NULL, cin VARCHAR(20) DEFAULT NULL, age INT DEFAULT NULL, reset_token VARCHAR(255) DEFAULT NULL, reset_token_expires_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', verification_code VARCHAR(10) DEFAULT NULL, verification_code_expires_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        
        $this->addSql('CREATE TABLE salle_de_sport (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, zone VARCHAR(255) NOT NULL, region VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, id_user INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        
        $this->addSql('CREATE TABLE equipement (id INT AUTO_INCREMENT NOT NULL, id_salle INT NOT NULL, id_user INT NOT NULL, nom VARCHAR(255) NOT NULL, fonctionnement TINYINT(1) NOT NULL, prochaine_verification DATE NOT NULL, derniere_verification DATE NOT NULL, image VARCHAR(255) DEFAULT NULL, INDEX IDX_B8B4C6F3A0123F6C (id_salle), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        
        $this->addSql('CREATE TABLE exercice (id INT AUTO_INCREMENT NOT NULL, id_equipement INT NOT NULL, description VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, id_user INT NOT NULL, nom_exercice VARCHAR(255) NOT NULL, muscle VARCHAR(255) DEFAULT NULL, conseils VARCHAR(1000) DEFAULT NULL, INDEX IDX_E418C74D1D3E4624 (id_equipement), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        
        $this->addSql('CREATE TABLE produits (id_produit INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, categorie VARCHAR(50) DEFAULT NULL, prix NUMERIC(10, 2) NOT NULL, quantite_stock INT NOT NULL, disponible TINYINT(1) NOT NULL, image_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id_produit)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        
        $this->addSql('CREATE TABLE commandes (id_commande INT AUTO_INCREMENT NOT NULL, id_produit INT NOT NULL, id_user INT NOT NULL, nom_client VARCHAR(100) NOT NULL, adresse_livraison LONGTEXT NOT NULL, telephone VARCHAR(20) NOT NULL, quantite INT NOT NULL, date_commande DATETIME NOT NULL, statut_commande VARCHAR(20) NOT NULL, INDEX IDX_35D4282CF7384557 (id_produit), INDEX IDX_35D4282C6B3CA4B (id_user), PRIMARY KEY(id_commande)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        
        $this->addSql('CREATE TABLE post (idp INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, date_u DATE NOT NULL, image VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, idUser INT DEFAULT NULL, INDEX IDX_5A8A6C8DFE6E88D7 (idUser), PRIMARY KEY(idp)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        
        $this->addSql('CREATE TABLE comment (id INT NOT NULL, comment VARCHAR(255) NOT NULL, date DATE NOT NULL, likes INT NOT NULL, idPost INT DEFAULT NULL, idUser INT DEFAULT NULL, INDEX IDX_9474526C29773213 (idPost), INDEX IDX_9474526CFE6E88D7 (idUser), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        
        $this->addSql('CREATE TABLE comment_like (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, comment_id INT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_8A55E25FA76ED395 (user_id), INDEX IDX_8A55E25FF8697D13 (comment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        
        $this->addSql('CREATE TABLE historique (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(255) NOT NULL, salle VARCHAR(255) NOT NULL, date DATE NOT NULL, user INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Add foreign key constraints
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282CF7384557 FOREIGN KEY (id_produit) REFERENCES produits (id_produit)');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282C6B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C29773213 FOREIGN KEY (idPost) REFERENCES post (idp) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CFE6E88D7 FOREIGN KEY (idUser) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment_like ADD CONSTRAINT FK_8A55E25FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment_like ADD CONSTRAINT FK_8A55E25FF8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equipement ADD CONSTRAINT FK_B8B4C6F3A0123F6C FOREIGN KEY (id_salle) REFERENCES salle_de_sport (id)');
        $this->addSql('ALTER TABLE exercice ADD CONSTRAINT FK_E418C74D1D3E4624 FOREIGN KEY (id_equipement) REFERENCES equipement (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DFE6E88D7 FOREIGN KEY (idUser) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282CF7384557');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282C6B3CA4B');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C29773213');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CFE6E88D7');
        $this->addSql('ALTER TABLE comment_like DROP FOREIGN KEY FK_8A55E25FA76ED395');
        $this->addSql('ALTER TABLE comment_like DROP FOREIGN KEY FK_8A55E25FF8697D13');
        $this->addSql('ALTER TABLE equipement DROP FOREIGN KEY FK_B8B4C6F3A0123F6C');
        $this->addSql('ALTER TABLE exercice DROP FOREIGN KEY FK_E418C74D1D3E4624');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DFE6E88D7');
        $this->addSql('DROP TABLE commandes');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE comment_like');
        $this->addSql('DROP TABLE equipement');
        $this->addSql('DROP TABLE exercice');
        $this->addSql('DROP TABLE historique');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE produits');
        $this->addSql('DROP TABLE salle_de_sport');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
