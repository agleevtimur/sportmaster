<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211101141332 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE store_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE inventory (quantity SMALLINT NOT NULL, store_id SMALLINT NOT NULL, sdk INT NOT NULL, PRIMARY KEY(sdk, store_id))');
        $this->addSql('CREATE INDEX IDX_B12D4A3672071968 ON inventory (sdk)');
        $this->addSql('CREATE INDEX IDX_B12D4A36B092A811 ON inventory (store_id)');
        $this->addSql('CREATE TABLE product (sdk INT NOT NULL, sku VARCHAR(11) NOT NULL, PRIMARY KEY(sdk))');
        $this->addSql('CREATE TABLE store (id SMALLINT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE inventory ADD CONSTRAINT FK_B12D4A3672071968 FOREIGN KEY (sdk) REFERENCES product (sdk) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE inventory ADD CONSTRAINT FK_B12D4A36B092A811 FOREIGN KEY (store_id) REFERENCES store (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE inventory DROP CONSTRAINT FK_B12D4A3672071968');
        $this->addSql('ALTER TABLE inventory DROP CONSTRAINT FK_B12D4A36B092A811');
        $this->addSql('DROP SEQUENCE store_id_seq CASCADE');
        $this->addSql('DROP TABLE inventory');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE store');
    }
}
