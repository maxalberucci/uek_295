<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230504093510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE kommentare (id INT AUTO_INCREMENT NOT NULL, produkt_id INT NOT NULL, kommentare VARCHAR(200) NOT NULL, rezensionen INT NOT NULL, INDEX IDX_1B7A0E7175F42D9B (produkt_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produkt (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, preis DOUBLE PRECISION NOT NULL, bestand INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE kommentare ADD CONSTRAINT FK_1B7A0E7175F42D9B FOREIGN KEY (produkt_id) REFERENCES produkt (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE kommentare DROP FOREIGN KEY FK_1B7A0E7175F42D9B');
        $this->addSql('DROP TABLE kommentare');
        $this->addSql('DROP TABLE produkt');
    }
}
