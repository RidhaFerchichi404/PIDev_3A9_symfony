<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250412162555 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE equipement (id INT AUTO_INCREMENT NOT NULL, id_salle INT NOT NULL, id_user INT NOT NULL, nom VARCHAR(255) NOT NULL, fonctionnement TINYINT(1) NOT NULL, prochaine_verification DATE NOT NULL, derniere_verification DATE NOT NULL, INDEX IDX_B8B4C6F3A0123F6C (id_salle), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercice (id INT AUTO_INCREMENT NOT NULL, id_equipement INT NOT NULL, description VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, id_user INT NOT NULL, nom_exercice VARCHAR(255) NOT NULL, muscle VARCHAR(255) DEFAULT NULL, conseils VARCHAR(1000) DEFAULT NULL, INDEX IDX_E418C74D1D3E4624 (id_equipement), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salle_de_sport (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, zone VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, id_user INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE equipement ADD CONSTRAINT FK_B8B4C6F3A0123F6C FOREIGN KEY (id_salle) REFERENCES salle_de_sport (id)');
        $this->addSql('ALTER TABLE exercice ADD CONSTRAINT FK_E418C74D1D3E4624 FOREIGN KEY (id_equipement) REFERENCES equipement (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipement DROP FOREIGN KEY FK_B8B4C6F3A0123F6C');
        $this->addSql('ALTER TABLE exercice DROP FOREIGN KEY FK_E418C74D1D3E4624');
        $this->addSql('DROP TABLE equipement');
        $this->addSql('DROP TABLE exercice');
        $this->addSql('DROP TABLE salle_de_sport');
    }
}
