<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220213005505 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE depense (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE depense_fonction (depense_id INT NOT NULL, fonction_id INT NOT NULL, INDEX IDX_D9656B9641D81563 (depense_id), INDEX IDX_D9656B9657889920 (fonction_id), PRIMARY KEY(depense_id, fonction_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fonction (id INT AUTO_INCREMENT NOT NULL, nom_f VARCHAR(255) NOT NULL, salaire INT NOT NULL, nb_heure INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personnel (id INT AUTO_INCREMENT NOT NULL, fonction_id INT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, photo VARCHAR(255) NOT NULL, emplois VARCHAR(255) NOT NULL, INDEX IDX_A6BCF3DE57889920 (fonction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE depense_fonction ADD CONSTRAINT FK_D9656B9641D81563 FOREIGN KEY (depense_id) REFERENCES depense (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE depense_fonction ADD CONSTRAINT FK_D9656B9657889920 FOREIGN KEY (fonction_id) REFERENCES fonction (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE personnel ADD CONSTRAINT FK_A6BCF3DE57889920 FOREIGN KEY (fonction_id) REFERENCES fonction (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE depense_fonction DROP FOREIGN KEY FK_D9656B9641D81563');
        $this->addSql('ALTER TABLE depense_fonction DROP FOREIGN KEY FK_D9656B9657889920');
        $this->addSql('ALTER TABLE personnel DROP FOREIGN KEY FK_A6BCF3DE57889920');
        $this->addSql('DROP TABLE depense');
        $this->addSql('DROP TABLE depense_fonction');
        $this->addSql('DROP TABLE fonction');
        $this->addSql('DROP TABLE personnel');
    }
}
