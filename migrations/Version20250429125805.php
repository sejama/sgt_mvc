<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250429125805 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        //$this->addSql('DROP INDEX IDX_FB58ADC783ECE76 ON partido_config');
        //$this->addSql('DROP INDEX IDX_FB58ADC71A8B6198 ON partido_config');
        $this->addSql('DROP INDEX UNIQ_FB58ADC783ECE76 ON partido_config');
        $this->addSql('DROP INDEX UNIQ_FB58ADC71A8B6198 ON partido_config');
        $this->addSql('ALTER TABLE partido_config ADD perdedor_partido1_id INT DEFAULT NULL, ADD perdedor_partido2_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE partido_config ADD CONSTRAINT FK_FB58ADC7EDE271C1 FOREIGN KEY (perdedor_partido1_id) REFERENCES partido (id)');
        $this->addSql('ALTER TABLE partido_config ADD CONSTRAINT FK_FB58ADC7FF57DE2F FOREIGN KEY (perdedor_partido2_id) REFERENCES partido (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FB58ADC7EDE271C1 ON partido_config (perdedor_partido1_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FB58ADC7FF57DE2F ON partido_config (perdedor_partido2_id)');
        $this->addSql('CREATE UNIQUE INDEX partido_config_grupo_posicion_1 ON partido_config (grupo_equipo1_id, posicion_equipo1)');
        $this->addSql('CREATE UNIQUE INDEX partido_config_grupo_posicion_2 ON partido_config (grupo_equipo2_id, posicion_equipo2)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FB58ADC783ECE76 ON partido_config (grupo_equipo1_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FB58ADC71A8B6198 ON partido_config (grupo_equipo2_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partido_config DROP FOREIGN KEY FK_FB58ADC7EDE271C1');
        $this->addSql('ALTER TABLE partido_config DROP FOREIGN KEY FK_FB58ADC7FF57DE2F');
        $this->addSql('DROP INDEX UNIQ_FB58ADC7EDE271C1 ON partido_config');
        $this->addSql('DROP INDEX UNIQ_FB58ADC7FF57DE2F ON partido_config');
        $this->addSql('DROP INDEX partido_config_grupo_posicion_1 ON partido_config');
        $this->addSql('DROP INDEX partido_config_grupo_posicion_2 ON partido_config');
        $this->addSql('DROP INDEX UNIQ_FB58ADC783ECE76 ON partido_config');
        $this->addSql('DROP INDEX UNIQ_FB58ADC71A8B6198 ON partido_config');
        $this->addSql('ALTER TABLE partido_config DROP perdedor_partido1_id, DROP perdedor_partido2_id');
        //$this->addSql('CREATE INDEX IDX_FB58ADC783ECE76 ON partido_config (grupo_equipo1_id)');
        //$this->addSql('CREATE INDEX IDX_FB58ADC71A8B6198 ON partido_config (grupo_equipo2_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FB58ADC783ECE76 ON partido_config (grupo_equipo1_id, posicion_equipo1)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FB58ADC71A8B6198 ON partido_config (grupo_equipo2_id, posicion_equipo2)');
    }
}
