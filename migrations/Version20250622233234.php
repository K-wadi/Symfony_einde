<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250622233234 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update user roles column to JSON and create missing tables';
    }

    public function up(Schema $schema): void
    {
        // Create tables if they don't exist
        $this->addSql('CREATE TABLE IF NOT EXISTS `user` (
            id INT AUTO_INCREMENT NOT NULL, 
            email VARCHAR(180) NOT NULL, 
            roles JSON NOT NULL, 
            password VARCHAR(255) NOT NULL, 
            UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE IF NOT EXISTS product (
            id INT AUTO_INCREMENT NOT NULL, 
            naam VARCHAR(255) NOT NULL, 
            prijs NUMERIC(10, 2) NOT NULL, 
            beschrijving LONGTEXT DEFAULT NULL, 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE IF NOT EXISTS bestelling (
            id INT AUTO_INCREMENT NOT NULL, 
            klantnaam VARCHAR(255) NOT NULL, 
            datum DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE IF NOT EXISTS bestelling_product (
            bestelling_id INT NOT NULL, 
            product_id INT NOT NULL, 
            INDEX IDX_D73B86FAA2E63037 (bestelling_id), 
            INDEX IDX_D73B86FA4584665A (product_id), 
            PRIMARY KEY(bestelling_id, product_id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE bestelling_product ADD CONSTRAINT FK_D73B86FAA2E63037 FOREIGN KEY (bestelling_id) REFERENCES bestelling (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bestelling_product ADD CONSTRAINT FK_D73B86FA4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');

        // Update existing user table if needed
        $this->addSql('ALTER TABLE user MODIFY roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bestelling_product DROP FOREIGN KEY FK_D73B86FAA2E63037');
        $this->addSql('ALTER TABLE bestelling_product DROP FOREIGN KEY FK_D73B86FA4584665A');
        $this->addSql('DROP TABLE bestelling_product');
        $this->addSql('DROP TABLE bestelling');
        $this->addSql('DROP TABLE product');
        $this->addSql('ALTER TABLE `user` CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
