<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260429080040 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_plant ADD CONSTRAINT FK_49C1F62AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_plant ADD CONSTRAINT FK_49C1F62A1D935652 FOREIGN KEY (plant_id) REFERENCES plant (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_plant DROP FOREIGN KEY FK_49C1F62AA76ED395');
        $this->addSql('ALTER TABLE user_plant DROP FOREIGN KEY FK_49C1F62A1D935652');
    }
}
