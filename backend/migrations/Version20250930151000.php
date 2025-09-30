<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250930151000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add cart_id and cart_history_id fields to users table and create user-cart relation tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users ADD cart_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD cart_history_id INT DEFAULT NULL');
        
        $this->addSql('CREATE TABLE user_carts (user_id INT NOT NULL, cart_id INT NOT NULL, INDEX IDX_FC14F745A76ED395 (user_id), INDEX IDX_FC14F7451AD5CDBF (cart_id), PRIMARY KEY(user_id, cart_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        
        $this->addSql('CREATE TABLE user_cart_histories (user_id INT NOT NULL, cart_history_id INT NOT NULL, INDEX IDX_D4E8A8A7A76ED395 (user_id), INDEX IDX_D4E8A8A75C2BC5D (cart_history_id), PRIMARY KEY(user_id, cart_history_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        
        $this->addSql('ALTER TABLE user_carts ADD CONSTRAINT FK_FC14F745A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_carts ADD CONSTRAINT FK_FC14F7451AD5CDBF FOREIGN KEY (cart_id) REFERENCES carts (cart_id) ON DELETE CASCADE');
        
        $this->addSql('ALTER TABLE user_cart_histories ADD CONSTRAINT FK_D4E8A8A7A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_cart_histories ADD CONSTRAINT FK_D4E8A8A75C2BC5D FOREIGN KEY (cart_history_id) REFERENCES cart_histories (cart_history_id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user_carts DROP FOREIGN KEY FK_FC14F745A76ED395');
        $this->addSql('ALTER TABLE user_carts DROP FOREIGN KEY FK_FC14F7451AD5CDBF');
        $this->addSql('ALTER TABLE user_cart_histories DROP FOREIGN KEY FK_D4E8A8A7A76ED395');
        $this->addSql('ALTER TABLE user_cart_histories DROP FOREIGN KEY FK_D4E8A8A75C2BC5D');
        
        $this->addSql('DROP TABLE user_carts');
        $this->addSql('DROP TABLE user_cart_histories');
        
        $this->addSql('ALTER TABLE users DROP cart_id');
        $this->addSql('ALTER TABLE users DROP cart_history_id');
    }
}