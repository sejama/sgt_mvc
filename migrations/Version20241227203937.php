<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241227203937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipo ADD estado VARCHAR(32) NOT NULL');
        $this->addSql('ALTER TABLE grupo ADD estado VARCHAR(32) NOT NULL');
        $this->addSql('ALTER TABLE partido ADD estado VARCHAR(32) NOT NULL, CHANGE equipo_local_id equipo_local_id INT DEFAULT NULL, CHANGE equipo_visitante_id equipo_visitante_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipo DROP estado');
        $this->addSql('ALTER TABLE grupo DROP estado');
        $this->addSql('ALTER TABLE partido DROP estado, CHANGE equipo_local_id equipo_local_id INT NOT NULL, CHANGE equipo_visitante_id equipo_visitante_id INT NOT NULL');
    }
}
