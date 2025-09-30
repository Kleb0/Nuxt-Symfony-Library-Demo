<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250929213219 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create status table with User and Admin entries';
    }

    public function up(Schema $schema): void
    {
        // Create the status table
        $this->addSql('CREATE TABLE status (status_id INT AUTO_INCREMENT NOT NULL, status_name VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_7B00651C9C8E1E21 (status_name), PRIMARY KEY(status_id)) DEFAULT CHARACTER SET utf8mb4');
        
        // Insert initial data
        $this->addSql('INSERT INTO status (status_name) VALUES (\'User\')');
        $this->addSql('INSERT INTO status (status_name) VALUES (\'Admin\')');
    }

    public function down(Schema $schema): void
    {
        // Drop the status table
        $this->addSql('DROP TABLE status');
    }
}
