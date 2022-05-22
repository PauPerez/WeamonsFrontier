<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220520152827 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tipus ADD CONSTRAINT FK_43EBCCFED7C99BD5 FOREIGN KEY (debilidad_id) REFERENCES tipus (id)');
        $this->addSql('ALTER TABLE tipus ADD CONSTRAINT FK_43EBCCFE81F3C672 FOREIGN KEY (fortaleza_id) REFERENCES tipus (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_43EBCCFED7C99BD5 ON tipus (debilidad_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_43EBCCFE81F3C672 ON tipus (fortaleza_id)');
        $this->addSql('ALTER TABLE usuari CHANGE verification_token verification_token VARCHAR(255) DEFAULT NULL, CHANGE roles roles JSON NOT NULL, CHANGE email email VARCHAR(180) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_68CC94FFE7927C74 ON usuari (email)');
        $this->addSql('ALTER TABLE weamon ADD preevolucio VARCHAR(255) DEFAULT NULL, ADD evolucio VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tipus DROP FOREIGN KEY FK_43EBCCFED7C99BD5');
        $this->addSql('ALTER TABLE tipus DROP FOREIGN KEY FK_43EBCCFE81F3C672');
        $this->addSql('DROP INDEX UNIQ_43EBCCFED7C99BD5 ON tipus');
        $this->addSql('DROP INDEX UNIQ_43EBCCFE81F3C672 ON tipus');
        $this->addSql('DROP INDEX UNIQ_68CC94FFE7927C74 ON usuari');
        $this->addSql('ALTER TABLE usuari CHANGE email email VARCHAR(255) NOT NULL, CHANGE roles roles VARCHAR(255) NOT NULL, CHANGE verification_token verification_token VARCHAR(500) DEFAULT NULL');
        $this->addSql('ALTER TABLE weamon DROP preevolucio, DROP evolucio');
    }
}
