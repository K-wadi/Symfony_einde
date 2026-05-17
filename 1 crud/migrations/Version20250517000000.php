<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Tabellen vendor + smartphone (ERD / presentatie form slide 7).
 */
final class Version20250517000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Maakt vendor en smartphone tabellen aan voor opdracht CRUD';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE vendor (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE smartphone (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, memory INT NOT NULL, color VARCHAR(255) NOT NULL, price NUMERIC(7, 2) NOT NULL, description LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, vendor_id INT NOT NULL, INDEX IDX_8AEE44D9F603EE73 (vendor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE smartphone ADD CONSTRAINT FK_8AEE44D9F603EE73 FOREIGN KEY (vendor_id) REFERENCES vendor (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE smartphone DROP FOREIGN KEY FK_8AEE44D9F603EE73');
        $this->addSql('DROP TABLE smartphone');
        $this->addSql('DROP TABLE vendor');
    }
}
