<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200729152421 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address ADD client_id INT DEFAULT NULL, ADD type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F8119EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_D4E6F8119EB6921 ON address (client_id)');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C744045579D0C0E4');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455EBF23851');
        $this->addSql('DROP INDEX UNIQ_C744045579D0C0E4 ON client');
        $this->addSql('DROP INDEX UNIQ_C7440455EBF23851 ON client');
        $this->addSql('ALTER TABLE client DROP billing_address_id, DROP delivery_address_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F8119EB6921');
        $this->addSql('DROP INDEX IDX_D4E6F8119EB6921 ON address');
        $this->addSql('ALTER TABLE address DROP client_id, DROP type');
        $this->addSql('ALTER TABLE client ADD billing_address_id INT NOT NULL, ADD delivery_address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C744045579D0C0E4 FOREIGN KEY (billing_address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455EBF23851 FOREIGN KEY (delivery_address_id) REFERENCES address (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C744045579D0C0E4 ON client (billing_address_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455EBF23851 ON client (delivery_address_id)');
    }
}
