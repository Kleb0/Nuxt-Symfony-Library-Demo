<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250930150000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Cart and CartHistory entities with their relation tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE carts (cart_id INT AUTO_INCREMENT NOT NULL, total_price NUMERIC(10, 2) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(cart_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        
        $this->addSql('CREATE TABLE cart_histories (cart_history_id INT AUTO_INCREMENT NOT NULL, list_cart_id JSON NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(cart_history_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        
        $this->addSql('CREATE TABLE cart_items (cart_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_BEF484451AD5CDBF (cart_id), INDEX IDX_BEF4844516A2B381 (book_id), PRIMARY KEY(cart_id, book_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        
        $this->addSql('CREATE TABLE cart_history_carts (cart_history_id INT NOT NULL, cart_id INT NOT NULL, INDEX IDX_E8B7F8765C2BC5D (cart_history_id), INDEX IDX_E8B7F8761AD5CDBF (cart_id), PRIMARY KEY(cart_history_id, cart_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        
        $this->addSql('ALTER TABLE cart_items ADD CONSTRAINT FK_BEF484451AD5CDBF FOREIGN KEY (cart_id) REFERENCES carts (cart_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cart_items ADD CONSTRAINT FK_BEF4844516A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
        
        $this->addSql('ALTER TABLE cart_history_carts ADD CONSTRAINT FK_E8B7F8765C2BC5D FOREIGN KEY (cart_history_id) REFERENCES cart_histories (cart_history_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cart_history_carts ADD CONSTRAINT FK_E8B7F8761AD5CDBF FOREIGN KEY (cart_id) REFERENCES carts (cart_id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE cart_items DROP FOREIGN KEY FK_BEF484451AD5CDBF');
        $this->addSql('ALTER TABLE cart_items DROP FOREIGN KEY FK_BEF4844516A2B381');
        $this->addSql('ALTER TABLE cart_history_carts DROP FOREIGN KEY FK_E8B7F8765C2BC5D');
        $this->addSql('ALTER TABLE cart_history_carts DROP FOREIGN KEY FK_E8B7F8761AD5CDBF');
        
        $this->addSql('DROP TABLE cart_items');
        $this->addSql('DROP TABLE cart_history_carts');
        $this->addSql('DROP TABLE carts');
        $this->addSql('DROP TABLE cart_histories');
    }
}