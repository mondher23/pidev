<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220304124048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649C9C7CEB6');
        $this->addSql('ALTER TABLE user ADD code VARCHAR(5) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649C9C7CEB6 FOREIGN KEY (carte_id) REFERENCES cartefidelite (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649C9C7CEB6');
        $this->addSql('ALTER TABLE user DROP code');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649C9C7CEB6 FOREIGN KEY (carte_id) REFERENCES cartefidelite (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
