<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260520082042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plant ADD scientific_name JSON DEFAULT NULL, ADD family VARCHAR(255) DEFAULT NULL, ADD type VARCHAR(255) DEFAULT NULL, ADD dimensions JSON DEFAULT NULL, ADD cycle VARCHAR(255) DEFAULT NULL, ADD maintenance VARCHAR(255) DEFAULT NULL, ADD growth_rate VARCHAR(255) DEFAULT NULL, ADD care_level VARCHAR(255) DEFAULT NULL, ADD description LONGTEXT DEFAULT NULL, ADD watering_quality JSON DEFAULT NULL, ADD watering_period JSON DEFAULT NULL, ADD sunlight_duration JSON DEFAULT NULL, ADD pruning_month JSON DEFAULT NULL, ADD attracts JSON DEFAULT NULL, ADD propagation JSON DEFAULT NULL, ADD hardiness JSON DEFAULT NULL, ADD flowers TINYINT DEFAULT NULL, ADD flowering_season VARCHAR(255) DEFAULT NULL, ADD medicinal TINYINT DEFAULT NULL, ADD poisonous_to_humans TINYINT DEFAULT NULL, ADD poisonous_to_pets TINYINT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_plant ADD CONSTRAINT FK_49C1F62AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_plant ADD CONSTRAINT FK_49C1F62A1D935652 FOREIGN KEY (plant_id) REFERENCES plant (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plant DROP scientific_name, DROP family, DROP type, DROP dimensions, DROP cycle, DROP maintenance, DROP growth_rate, DROP care_level, DROP description, DROP watering_quality, DROP watering_period, DROP sunlight_duration, DROP pruning_month, DROP attracts, DROP propagation, DROP hardiness, DROP flowers, DROP flowering_season, DROP medicinal, DROP poisonous_to_humans, DROP poisonous_to_pets');
        $this->addSql('ALTER TABLE user_plant DROP FOREIGN KEY FK_49C1F62AA76ED395');
        $this->addSql('ALTER TABLE user_plant DROP FOREIGN KEY FK_49C1F62A1D935652');
    }
}
