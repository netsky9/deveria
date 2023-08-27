<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230817164318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE author CHANGE modified_on modified_on DATETIME on update CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE book CHANGE publish_at publish_at INT NOT NULL, CHANGE modified_on modified_on DATETIME on update CURRENT_TIMESTAMP');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE author CHANGE modified_on modified_on DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE book CHANGE publish_at publish_at DATE NOT NULL, CHANGE modified_on modified_on DATETIME DEFAULT NULL');
    }
}
