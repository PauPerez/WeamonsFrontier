<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220425154602 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        //$this->addSql('CREATE UNIQUE INDEX UNIQ_68CC94FFE7927C74 ON usuari (email)');
        $this->addSql('ALTER TABLE weamon ADD img_b VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        //$this->addSql('DROP INDEX UNIQ_68CC94FFE7927C74 ON usuari');
        $this->addSql('ALTER TABLE weamon DROP img_b');
    }
}
