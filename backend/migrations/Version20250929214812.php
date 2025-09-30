<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250929214812 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create book table';
    }

    public function up(Schema $schema): void
    {
        // Create the book table
        $this->addSql('CREATE TABLE book (
            id INT AUTO_INCREMENT NOT NULL, 
            titre VARCHAR(255) NOT NULL, 
            unit_price NUMERIC(10, 2) NOT NULL, 
            resume LONGTEXT DEFAULT NULL, 
            image VARCHAR(255) NOT NULL, 
            categorie VARCHAR(255) NOT NULL, 
            auteur VARCHAR(255) NOT NULL, 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4');
    }

    public function down(Schema $schema): void
    {
        // Drop the book table
        $this->addSql('DROP TABLE book');
    }
}
