<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211012080753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD inscrire_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6495A9C42F6 FOREIGN KEY (inscrire_id) REFERENCES inscrire (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6495A9C42F6 ON user (inscrire_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6495A9C42F6');
        $this->addSql('DROP INDEX UNIQ_8D93D6495A9C42F6 ON user');
        $this->addSql('ALTER TABLE user DROP inscrire_id');
    }
}
