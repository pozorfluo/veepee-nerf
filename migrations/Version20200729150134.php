<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200729150134 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, country_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, address_complement VARCHAR(255) DEFAULT NULL, city VARCHAR(255) NOT NULL, zip_code VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, INDEX IDX_D4E6F81F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, billing_address_id INT NOT NULL, delivery_address_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_C744045579D0C0E4 (billing_address_id), UNIQUE INDEX UNIQ_C7440455EBF23851 (delivery_address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_info (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, product_id INT NOT NULL, payment_method VARCHAR(255) NOT NULL, total INT NOT NULL, created_at DATETIME NOT NULL, status VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_86780B4019EB6921 (client_id), INDEX IDX_86780B404584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, sku VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, price INT NOT NULL, msrp INT NOT NULL, inventory INT NOT NULL, image VARCHAR(255) DEFAULT NULL, hot TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C744045579D0C0E4 FOREIGN KEY (billing_address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455EBF23851 FOREIGN KEY (delivery_address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE order_info ADD CONSTRAINT FK_86780B4019EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE order_info ADD CONSTRAINT FK_86780B404584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C744045579D0C0E4');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455EBF23851');
        $this->addSql('ALTER TABLE order_info DROP FOREIGN KEY FK_86780B4019EB6921');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81F92F3E70');
        $this->addSql('ALTER TABLE order_info DROP FOREIGN KEY FK_86780B404584665A');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE order_info');
        $this->addSql('DROP TABLE product');
    }
}
