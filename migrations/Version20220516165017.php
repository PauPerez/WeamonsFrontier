<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220516165017 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usuari ADD change_password_token VARCHAR(255) DEFAULT NULL');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tipus DROP FOREIGN KEY FK_43EBCCFED7C99BD5');
        $this->addSql('ALTER TABLE tipus DROP FOREIGN KEY FK_43EBCCFE81F3C672');
        $this->addSql('DROP INDEX UNIQ_43EBCCFED7C99BD5 ON tipus');
        $this->addSql('DROP INDEX UNIQ_43EBCCFE81F3C672 ON tipus');
        $this->addSql('DROP INDEX UNIQ_68CC94FFE7927C74 ON usuari');
        $this->addSql('ALTER TABLE usuari DROP change_password_token');
    }
}
