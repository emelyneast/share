<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211012080651 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(30) NOT NULL, sujet VARCHAR(100) NOT NULL, email VARCHAR(100) NOT NULL, message LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fichier (id INT AUTO_INCREMENT NOT NULL, inscrire_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, date DATETIME NOT NULL, extention VARCHAR(5) NOT NULL, taille INT NOT NULL, original VARCHAR(100) NOT NULL, INDEX IDX_9B76551F5A9C42F6 (inscrire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inscrire (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(30) NOT NULL, prenom VARCHAR(50) NOT NULL, date_n DATE NOT NULL, date_in DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livredor (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(30) NOT NULL, message LONGTEXT NOT NULL, nombre INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE telecharger (id INT AUTO_INCREMENT NOT NULL, inscrire_id INT DEFAULT NULL, fichier_id INT DEFAULT NULL, nb INT NOT NULL, INDEX IDX_E06A3C345A9C42F6 (inscrire_id), INDEX IDX_E06A3C34F915CFE (fichier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE theme (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE theme_fichier (theme_id INT NOT NULL, fichier_id INT NOT NULL, INDEX IDX_C215503C59027487 (theme_id), INDEX IDX_C215503CF915CFE (fichier_id), PRIMARY KEY(theme_id, fichier_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fichier ADD CONSTRAINT FK_9B76551F5A9C42F6 FOREIGN KEY (inscrire_id) REFERENCES inscrire (id)');
        $this->addSql('ALTER TABLE telecharger ADD CONSTRAINT FK_E06A3C345A9C42F6 FOREIGN KEY (inscrire_id) REFERENCES inscrire (id)');
        $this->addSql('ALTER TABLE telecharger ADD CONSTRAINT FK_E06A3C34F915CFE FOREIGN KEY (fichier_id) REFERENCES fichier (id)');
        $this->addSql('ALTER TABLE theme_fichier ADD CONSTRAINT FK_C215503C59027487 FOREIGN KEY (theme_id) REFERENCES theme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE theme_fichier ADD CONSTRAINT FK_C215503CF915CFE FOREIGN KEY (fichier_id) REFERENCES fichier (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE telecharger DROP FOREIGN KEY FK_E06A3C34F915CFE');
        $this->addSql('ALTER TABLE theme_fichier DROP FOREIGN KEY FK_C215503CF915CFE');
        $this->addSql('ALTER TABLE fichier DROP FOREIGN KEY FK_9B76551F5A9C42F6');
        $this->addSql('ALTER TABLE telecharger DROP FOREIGN KEY FK_E06A3C345A9C42F6');
        $this->addSql('ALTER TABLE theme_fichier DROP FOREIGN KEY FK_C215503C59027487');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE fichier');
        $this->addSql('DROP TABLE inscrire');
        $this->addSql('DROP TABLE livredor');
        $this->addSql('DROP TABLE telecharger');
        $this->addSql('DROP TABLE theme');
        $this->addSql('DROP TABLE theme_fichier');
        $this->addSql('DROP TABLE user');
    }
}
