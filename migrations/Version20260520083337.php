<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260520083337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plant ADD perenual_id INT DEFAULT NULL, ADD other_name JSON DEFAULT NULL, ADD origin JSON DEFAULT NULL, ADD plant_anatomy JSON DEFAULT NULL, ADD pruning_count JSON DEFAULT NULL, ADD seeds INT DEFAULT NULL, ADD hardiness_location JSON DEFAULT NULL, ADD soil JSON DEFAULT NULL, ADD pest_susceptibility JSON DEFAULT NULL, ADD cones TINYINT DEFAULT NULL, ADD fruits TINYINT DEFAULT NULL, ADD edible_fruit TINYINT DEFAULT NULL, ADD fruiting_season VARCHAR(255) DEFAULT NULL, ADD harvest_season VARCHAR(255) DEFAULT NULL, ADD harvest_method VARCHAR(255) DEFAULT NULL, ADD leaf TINYINT DEFAULT NULL, ADD edible_leaf TINYINT DEFAULT NULL, ADD drought_tolerant TINYINT DEFAULT NULL, ADD salt_tolerant TINYINT DEFAULT NULL, ADD thorny TINYINT DEFAULT NULL, ADD invasive TINYINT DEFAULT NULL, ADD rare TINYINT DEFAULT NULL, ADD tropical TINYINT DEFAULT NULL, ADD cuisine TINYINT DEFAULT NULL, ADD indoor TINYINT DEFAULT NULL, ADD other_images JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE user_plant ADD CONSTRAINT FK_49C1F62AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_plant ADD CONSTRAINT FK_49C1F62A1D935652 FOREIGN KEY (plant_id) REFERENCES plant (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plant DROP perenual_id, DROP other_name, DROP origin, DROP plant_anatomy, DROP pruning_count, DROP seeds, DROP hardiness_location, DROP soil, DROP pest_susceptibility, DROP cones, DROP fruits, DROP edible_fruit, DROP fruiting_season, DROP harvest_season, DROP harvest_method, DROP leaf, DROP edible_leaf, DROP drought_tolerant, DROP salt_tolerant, DROP thorny, DROP invasive, DROP rare, DROP tropical, DROP cuisine, DROP indoor, DROP other_images');
        $this->addSql('ALTER TABLE user_plant DROP FOREIGN KEY FK_49C1F62AA76ED395');
        $this->addSql('ALTER TABLE user_plant DROP FOREIGN KEY FK_49C1F62A1D935652');
    }
}
