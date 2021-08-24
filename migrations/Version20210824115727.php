<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210824115727 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon_team ADD id_team_id INT NOT NULL');
        $this->addSql('ALTER TABLE pokemon_team ADD CONSTRAINT FK_F849D85CF7F171DE FOREIGN KEY (id_team_id) REFERENCES team (id)');
        $this->addSql('CREATE INDEX IDX_F849D85CF7F171DE ON pokemon_team (id_team_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon_team DROP FOREIGN KEY FK_F849D85CF7F171DE');
        $this->addSql('DROP INDEX IDX_F849D85CF7F171DE ON pokemon_team');
        $this->addSql('ALTER TABLE pokemon_team DROP id_team_id');
    }
}
