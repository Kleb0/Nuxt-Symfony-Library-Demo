<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250930152000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create cart_item_quantities table for managing item quantities in carts';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE cart_item_quantities (id INT AUTO_INCREMENT NOT NULL, cart_id INT NOT NULL, book_id INT NOT NULL, quantity INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_C4E4C6D61AD5CDBF (cart_id), INDEX IDX_C4E4C6D616A2B381 (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        
        $this->addSql('ALTER TABLE cart_item_quantities ADD CONSTRAINT FK_C4E4C6D61AD5CDBF FOREIGN KEY (cart_id) REFERENCES carts (cart_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cart_item_quantities ADD CONSTRAINT FK_C4E4C6D616A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE cart_item_quantities DROP FOREIGN KEY FK_C4E4C6D61AD5CDBF');
        $this->addSql('ALTER TABLE cart_item_quantities DROP FOREIGN KEY FK_C4E4C6D616A2B381');
        $this->addSql('DROP TABLE cart_item_quantities');
    }
}