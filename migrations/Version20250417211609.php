<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250417211609 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('SET FOREIGN_KEY_CHECKS=0;');
        // Vos instructions SQL ici
        $this->addSql('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD14DBA13C9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD144B37A9A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD14DBA13C9
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_c11d7dd14dba13c9 ON promotion
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX promotion_ibfk_1 ON promotion (AbonnementID)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_c11d7dd144b37a9a ON promotion
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX FK_C11D7DD144B37A9A ON promotion (SalleID)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD144B37A9A FOREIGN KEY (SalleId) REFERENCES salledesport (SalleID)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD14DBA13C9 FOREIGN KEY (AbonnementID) REFERENCES abonnement (AbonnementID) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE salledesport ADD DateCreation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, DROP date_creation, CHANGE SalleID SalleID INT NOT NULL, CHANGE adresse Adresse VARCHAR(255) DEFAULT NULL, CHANGE ville Ville VARCHAR(100) DEFAULT NULL, CHANGE CodePostal CodePostal VARCHAR(20) DEFAULT NULL, CHANGE telephone Telephone VARCHAR(20) DEFAULT NULL, CHANGE email Email VARCHAR(100) DEFAULT NULL
        SQL);
    }
}
