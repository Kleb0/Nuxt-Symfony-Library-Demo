<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250930120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create user_status join table for Many-to-Many relationship';
    }

    public function up(Schema $schema): void
    {
        // Create the join table user_status
        $this->addSql('CREATE TABLE user_status (user_id INT NOT NULL, status_id INT NOT NULL, INDEX IDX_E50EE6CEA76ED395 (user_id), INDEX IDX_E50EE6CE6BF700BD (status_id), PRIMARY KEY(user_id, status_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_status ADD CONSTRAINT FK_E50EE6CEA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_status ADD CONSTRAINT FK_E50EE6CE6BF700BD FOREIGN KEY (status_id) REFERENCES status (status_id) ON DELETE CASCADE');
        
        // Migrate existing data from users.status_id to the join table
        $this->addSql('INSERT INTO user_status (user_id, status_id) SELECT id, status_id FROM users WHERE status_id IS NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // Drop the join table
        $this->addSql('ALTER TABLE user_status DROP FOREIGN KEY FK_E50EE6CEA76ED395');
        $this->addSql('ALTER TABLE user_status DROP FOREIGN KEY FK_E50EE6CE6BF700BD');
        $this->addSql('DROP TABLE user_status');
    }
}