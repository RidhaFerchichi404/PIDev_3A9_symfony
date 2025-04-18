<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250410142527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE commandes (id_commande INT AUTO_INCREMENT NOT NULL, id_user INT NOT NULL, nom_client VARCHAR(100) NOT NULL, adresse_livraison LONGTEXT NOT NULL, telephone VARCHAR(20) NOT NULL, quantite INT NOT NULL, date_commande DATETIME NOT NULL, statut_commande VARCHAR(20) NOT NULL, id_produit INT NOT NULL, INDEX IDX_35D4282CF7384557 (id_produit), PRIMARY KEY(id_commande)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE produits (id_produit INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, categorie VARCHAR(50) DEFAULT NULL, prix NUMERIC(10, 2) NOT NULL, quantite_stock INT NOT NULL, disponible TINYINT(1) NOT NULL, image_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id_produit)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commandes ADD CONSTRAINT FK_35D4282CF7384557 FOREIGN KEY (id_produit) REFERENCES produits (id_produit)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282CF7384557
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE commandes
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE produits
        SQL);
    }
}
