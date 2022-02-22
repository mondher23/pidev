<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220221233954 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE culture (ref VARCHAR(255) NOT NULL, pays VARCHAR(255) NOT NULL, texte VARCHAR(2000) NOT NULL, date_ajout DATE NOT NULL, flag VARCHAR(255) NOT NULL, PRIMARY KEY(ref)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(2000) NOT NULL, remise DOUBLE PRECISION NOT NULL, image VARCHAR(255) NOT NULL, exp_date DATE NOT NULL, expire TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE voyage (id INT AUTO_INCREMENT NOT NULL, offre_id INT DEFAULT NULL, id_u INT NOT NULL, date_dep DATE NOT NULL, heure_dep TIME NOT NULL, destination VARCHAR(255) NOT NULL, done TINYINT(1) NOT NULL, INDEX IDX_3F9D89554CC8505A (offre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE voyage ADD CONSTRAINT FK_3F9D89554CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE voyage DROP FOREIGN KEY FK_3F9D89554CC8505A');
        $this->addSql('DROP TABLE culture');
        $this->addSql('DROP TABLE offre');
        $this->addSql('DROP TABLE voyage');
    }
}
