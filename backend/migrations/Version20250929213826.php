<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250929213826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add mot_de_passe field to users table';
    }

    public function up(Schema $schema): void
    {
        // Add the mot_de_passe column to the users table
        $this->addSql('ALTER TABLE users ADD mot_de_passe VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // Remove the mot_de_passe column from the users table
        $this->addSql('ALTER TABLE users DROP mot_de_passe');
    }
}
