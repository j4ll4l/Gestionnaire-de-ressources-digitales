<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250908130650 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ressources (id INT AUTO_INCREMENT NOT NULL, id_section_id INT NOT NULL, nom VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_6A2CD5C77DA963AD (id_section_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ressources_tag (ressources_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_BA6C6CF53C361826 (ressources_id), INDEX IDX_BA6C6CF5BAD26311 (tag_id), PRIMARY KEY(ressources_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE section (id INT AUTO_INCREMENT NOT NULL, id_categorie_id INT NOT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_2D737AEF9F34925F (id_categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ressources ADD CONSTRAINT FK_6A2CD5C77DA963AD FOREIGN KEY (id_section_id) REFERENCES section (id)');
        $this->addSql('ALTER TABLE ressources_tag ADD CONSTRAINT FK_BA6C6CF53C361826 FOREIGN KEY (ressources_id) REFERENCES ressources (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ressources_tag ADD CONSTRAINT FK_BA6C6CF5BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE section ADD CONSTRAINT FK_2D737AEF9F34925F FOREIGN KEY (id_categorie_id) REFERENCES categorie (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ressources DROP FOREIGN KEY FK_6A2CD5C77DA963AD');
        $this->addSql('ALTER TABLE ressources_tag DROP FOREIGN KEY FK_BA6C6CF53C361826');
        $this->addSql('ALTER TABLE ressources_tag DROP FOREIGN KEY FK_BA6C6CF5BAD26311');
        $this->addSql('ALTER TABLE section DROP FOREIGN KEY FK_2D737AEF9F34925F');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE ressources');
        $this->addSql('DROP TABLE ressources_tag');
        $this->addSql('DROP TABLE section');
        $this->addSql('DROP TABLE tag');
    }
}
