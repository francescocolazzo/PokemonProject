<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210824022904 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pokemon_team (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, base_experience INT NOT NULL, sprite_image VARCHAR(255) NOT NULL, abilities VARCHAR(255) NOT NULL, types VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_team_team (pokemon_team_id INT NOT NULL, team_id INT NOT NULL, INDEX IDX_24E8B8424506A43F (pokemon_team_id), INDEX IDX_24E8B842296CD8AE (team_id), PRIMARY KEY(pokemon_team_id, team_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pokemon_team_team ADD CONSTRAINT FK_24E8B8424506A43F FOREIGN KEY (pokemon_team_id) REFERENCES pokemon_team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pokemon_team_team ADD CONSTRAINT FK_24E8B842296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon_team_team DROP FOREIGN KEY FK_24E8B8424506A43F');
        $this->addSql('DROP TABLE pokemon_team');
        $this->addSql('DROP TABLE pokemon_team_team');
    }
}
