<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250929214050 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add pseudo field to users table';
    }

    public function up(Schema $schema): void
    {
        // Add the pseudo column to the users table
        $this->addSql('ALTER TABLE users ADD pseudo VARCHAR(50) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E986CC499D ON users (pseudo)');
    }

    public function down(Schema $schema): void
    {
        // Remove the pseudo column from the users table
        $this->addSql('DROP INDEX UNIQ_1483A5E986CC499D ON users');
        $this->addSql('ALTER TABLE users DROP pseudo');
    }
}
