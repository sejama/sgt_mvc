<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250225142822 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE partido_config (id INT AUTO_INCREMENT NOT NULL, partido_id INT NOT NULL, grupo_equipo1_id INT DEFAULT NULL, grupo_equipo2_id INT DEFAULT NULL, ganador_partido1_id INT DEFAULT NULL, ganador_partido2_id INT DEFAULT NULL, posicion_equipo1 SMALLINT DEFAULT NULL, posicion_equipo2 SMALLINT DEFAULT NULL, nombre VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_FB58ADC711856EB4 (partido_id), UNIQUE INDEX UNIQ_FB58ADC783ECE76 (grupo_equipo1_id), UNIQUE INDEX UNIQ_FB58ADC71A8B6198 (grupo_equipo2_id), UNIQUE INDEX UNIQ_FB58ADC7A1716C4E (ganador_partido1_id), UNIQUE INDEX UNIQ_FB58ADC7B3C4C3A0 (ganador_partido2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE partido_config ADD CONSTRAINT FK_FB58ADC711856EB4 FOREIGN KEY (partido_id) REFERENCES partido (id)');
        $this->addSql('ALTER TABLE partido_config ADD CONSTRAINT FK_FB58ADC783ECE76 FOREIGN KEY (grupo_equipo1_id, posicion_equipo1) REFERENCES grupo (id)');
        $this->addSql('ALTER TABLE partido_config ADD CONSTRAINT FK_FB58ADC71A8B6198 FOREIGN KEY (grupo_equipo2_id, posicion_equipo2) REFERENCES grupo (id)');
        $this->addSql('ALTER TABLE partido_config ADD CONSTRAINT FK_FB58ADC7A1716C4E FOREIGN KEY (ganador_partido1_id) REFERENCES partido (id)');
        $this->addSql('ALTER TABLE partido_config ADD CONSTRAINT FK_FB58ADC7B3C4C3A0 FOREIGN KEY (ganador_partido2_id) REFERENCES partido (id)');
        $this->addSql('ALTER TABLE usuario CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partido_config DROP FOREIGN KEY FK_FB58ADC711856EB4');
        $this->addSql('ALTER TABLE partido_config DROP FOREIGN KEY FK_FB58ADC783ECE76');
        $this->addSql('ALTER TABLE partido_config DROP FOREIGN KEY FK_FB58ADC71A8B6198');
        $this->addSql('ALTER TABLE partido_config DROP FOREIGN KEY FK_FB58ADC7A1716C4E');
        $this->addSql('ALTER TABLE partido_config DROP FOREIGN KEY FK_FB58ADC7B3C4C3A0');
        $this->addSql('DROP TABLE partido_config');
        $this->addSql('ALTER TABLE usuario CHANGE roles roles JSON NOT NULL COLLATE `utf8mb4_bin`');
    }
}
