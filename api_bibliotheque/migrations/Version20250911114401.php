<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250911114401 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD published_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE ressources ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD published_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE section ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD published_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie DROP created_at, DROP published_at');
        $this->addSql('ALTER TABLE ressources DROP created_at, DROP published_at');
        $this->addSql('ALTER TABLE section DROP created_at, DROP published_at');
    }
}
