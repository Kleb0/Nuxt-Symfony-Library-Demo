<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250929212923 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Drop accounts table';
    }

    public function up(Schema $schema): void
    {
        // Drop the accounts table
        $this->addSql('DROP TABLE accounts');
    }

    public function down(Schema $schema): void
    {
        // Recreate the accounts table if needed
        $this->addSql('CREATE TABLE accounts (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
    }
}
