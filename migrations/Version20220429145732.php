<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220429145732 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE moviment ADD animation VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE usuari CHANGE verification_token verification_token VARCHAR(255) DEFAULT NULL, CHANGE roles roles JSON NOT NULL, CHANGE email email VARCHAR(180) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_68CC94FFE7927C74 ON usuari (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE moviment DROP animation');
        $this->addSql('DROP INDEX UNIQ_68CC94FFE7927C74 ON usuari');
        $this->addSql('ALTER TABLE usuari CHANGE email email VARCHAR(255) NOT NULL, CHANGE roles roles VARCHAR(255) NOT NULL, CHANGE verification_token verification_token VARCHAR(500) DEFAULT NULL');
    }
}
