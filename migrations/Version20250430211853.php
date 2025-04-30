<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250430211853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT NOT NULL, comment VARCHAR(255) NOT NULL, date DATE NOT NULL, likes INT NOT NULL, idPost INT DEFAULT NULL, idUser INT DEFAULT NULL, INDEX IDX_9474526C29773213 (idPost), INDEX IDX_9474526CFE6E88D7 (idUser), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (idp INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, date_u DATE NOT NULL, image VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, idUser INT DEFAULT NULL, INDEX IDX_5A8A6C8DFE6E88D7 (idUser), PRIMARY KEY(idp)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C29773213 FOREIGN KEY (idPost) REFERENCES post (idp) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CFE6E88D7 FOREIGN KEY (idUser) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DFE6E88D7 FOREIGN KEY (idUser) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C29773213');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CFE6E88D7');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DFE6E88D7');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE post');
    }
}
