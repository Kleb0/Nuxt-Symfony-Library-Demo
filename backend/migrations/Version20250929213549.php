<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250929213549 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create users table without foreign key to status table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE users (
            id INT AUTO_INCREMENT NOT NULL, 
            status_id INT NOT NULL, 
            nom VARCHAR(100) NOT NULL, 
            prenom VARCHAR(100) NOT NULL, 
            adresse LONGTEXT DEFAULT NULL, 
            mail VARCHAR(255) NOT NULL, 
            image_profil VARCHAR(255) DEFAULT NULL, 
            telephone VARCHAR(20) DEFAULT NULL, 
            created_at DATETIME NOT NULL, 
            updated_at DATETIME DEFAULT NULL, 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE users');
    }
}
