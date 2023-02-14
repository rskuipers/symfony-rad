<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230214175238 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE chuckle_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE chuckle (id INT NOT NULL, author_id INT NOT NULL, message TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6B26A6DEF675F31B ON chuckle (author_id)');
        $this->addSql('COMMENT ON COLUMN chuckle.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE chuckle ADD CONSTRAINT FK_6B26A6DEF675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE chuckle_id_seq CASCADE');
        $this->addSql('ALTER TABLE chuckle DROP CONSTRAINT FK_6B26A6DEF675F31B');
        $this->addSql('DROP TABLE chuckle');
    }
}
