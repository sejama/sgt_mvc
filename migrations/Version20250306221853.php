<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250306221853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cancha (id INT AUTO_INCREMENT NOT NULL, sede_id INT DEFAULT NULL, nombre VARCHAR(255) NOT NULL, descripcion VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9D09C782E19F41BF (sede_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categoria (id INT AUTO_INCREMENT NOT NULL, torneo_id INT DEFAULT NULL, nombre VARCHAR(128) NOT NULL, genero VARCHAR(255) NOT NULL, disputa LONGTEXT DEFAULT NULL, estado VARCHAR(32) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', nombre_corto VARCHAR(8) NOT NULL, INDEX IDX_4E10122DA0139802 (torneo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipo (id INT AUTO_INCREMENT NOT NULL, categoria_id INT NOT NULL, grupo_id INT DEFAULT NULL, nombre VARCHAR(128) NOT NULL, nombre_corto VARCHAR(16) NOT NULL, pais VARCHAR(128) DEFAULT NULL, provincia VARCHAR(128) DEFAULT NULL, localidad VARCHAR(128) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', estado VARCHAR(32) NOT NULL, numero SMALLINT NOT NULL, INDEX IDX_C49C530B3397707A (categoria_id), INDEX IDX_C49C530B9C833003 (grupo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE grupo (id INT AUTO_INCREMENT NOT NULL, categoria_id INT NOT NULL, nombre VARCHAR(25) NOT NULL, clasifica_oro INT NOT NULL, clasifica_plata INT DEFAULT NULL, clasifica_bronce INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', estado VARCHAR(32) NOT NULL, INDEX IDX_8C0E9BD33397707A (categoria_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jugador (id INT AUTO_INCREMENT NOT NULL, equipo_id INT DEFAULT NULL, nombre VARCHAR(128) NOT NULL, apellido VARCHAR(128) NOT NULL, tipo_documento VARCHAR(12) NOT NULL, numero_documento VARCHAR(15) NOT NULL, nacimiento DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', responsable TINYINT(1) NOT NULL, email VARCHAR(255) DEFAULT NULL, celular VARCHAR(32) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', tipo VARCHAR(64) NOT NULL, INDEX IDX_527D6F1823BFBED (equipo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partido (id INT AUTO_INCREMENT NOT NULL, cancha_id INT DEFAULT NULL, categoria_id INT NOT NULL, grupo_id INT DEFAULT NULL, equipo_local_id INT DEFAULT NULL, equipo_visitante_id INT DEFAULT NULL, horario DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', local_set1 SMALLINT DEFAULT NULL, local_set2 SMALLINT DEFAULT NULL, local_set3 SMALLINT DEFAULT NULL, local_set4 SMALLINT DEFAULT NULL, local_set5 SMALLINT DEFAULT NULL, visitante_set1 SMALLINT DEFAULT NULL, visitante_set2 SMALLINT DEFAULT NULL, visitante_set3 SMALLINT DEFAULT NULL, visitante_set4 SMALLINT DEFAULT NULL, visitante_set5 SMALLINT DEFAULT NULL, estado VARCHAR(32) NOT NULL, tipo VARCHAR(32) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', numero SMALLINT NOT NULL, INDEX IDX_4E79750B7997F36E (cancha_id), INDEX IDX_4E79750B3397707A (categoria_id), INDEX IDX_4E79750B9C833003 (grupo_id), INDEX IDX_4E79750B88774E73 (equipo_local_id), INDEX IDX_4E79750B8C243011 (equipo_visitante_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partido_config (id INT AUTO_INCREMENT NOT NULL, partido_id INT NOT NULL, grupo_equipo1_id INT DEFAULT NULL, grupo_equipo2_id INT DEFAULT NULL, ganador_partido1_id INT DEFAULT NULL, ganador_partido2_id INT DEFAULT NULL, posicion_equipo1 SMALLINT DEFAULT NULL, posicion_equipo2 SMALLINT DEFAULT NULL, nombre VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_FB58ADC711856EB4 (partido_id), UNIQUE INDEX UNIQ_FB58ADC783ECE76 (grupo_equipo1_id), UNIQUE INDEX UNIQ_FB58ADC71A8B6198 (grupo_equipo2_id), UNIQUE INDEX UNIQ_FB58ADC7A1716C4E (ganador_partido1_id), UNIQUE INDEX UNIQ_FB58ADC7B3C4C3A0 (ganador_partido2_id), UNIQUE INDEX partido_config_grupo_posicion_1 (grupo_equipo1_id, posicion_equipo1), UNIQUE INDEX partido_config_grupo_posicion_2 (grupo_equipo2_id, posicion_equipo2), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sede (id INT AUTO_INCREMENT NOT NULL, torneo_id INT DEFAULT NULL, nombre VARCHAR(255) NOT NULL, domicilio VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_2A9BE2D1A0139802 (torneo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE torneo (id INT AUTO_INCREMENT NOT NULL, creador_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, ruta VARCHAR(32) NOT NULL, descripcion VARCHAR(255) DEFAULT NULL, fecha_inicio_inscripcion DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', fecha_fin_inscripcion DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', fecha_inicio_torneo DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', fecha_fin_torneo DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', reglamento LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', estado VARCHAR(32) NOT NULL, UNIQUE INDEX UNIQ_7CEB63FE3A909126 (nombre), UNIQUE INDEX UNIQ_7CEB63FEC3AEF08C (ruta), INDEX IDX_7CEB63FE62F40C3D (creador_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE torneo_usuario (torneo_id INT NOT NULL, usuario_id INT NOT NULL, INDEX IDX_4633E7AFA0139802 (torneo_id), INDEX IDX_4633E7AFDB38439E (usuario_id), PRIMARY KEY(torneo_id, usuario_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(128) DEFAULT NULL, nombre VARCHAR(128) DEFAULT NULL, apellido VARCHAR(128) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cancha ADD CONSTRAINT FK_9D09C782E19F41BF FOREIGN KEY (sede_id) REFERENCES sede (id)');
        $this->addSql('ALTER TABLE categoria ADD CONSTRAINT FK_4E10122DA0139802 FOREIGN KEY (torneo_id) REFERENCES torneo (id)');
        $this->addSql('ALTER TABLE equipo ADD CONSTRAINT FK_C49C530B3397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id)');
        $this->addSql('ALTER TABLE equipo ADD CONSTRAINT FK_C49C530B9C833003 FOREIGN KEY (grupo_id) REFERENCES grupo (id)');
        $this->addSql('ALTER TABLE grupo ADD CONSTRAINT FK_8C0E9BD33397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id)');
        $this->addSql('ALTER TABLE jugador ADD CONSTRAINT FK_527D6F1823BFBED FOREIGN KEY (equipo_id) REFERENCES equipo (id)');
        $this->addSql('ALTER TABLE partido ADD CONSTRAINT FK_4E79750B7997F36E FOREIGN KEY (cancha_id) REFERENCES cancha (id)');
        $this->addSql('ALTER TABLE partido ADD CONSTRAINT FK_4E79750B3397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id)');
        $this->addSql('ALTER TABLE partido ADD CONSTRAINT FK_4E79750B9C833003 FOREIGN KEY (grupo_id) REFERENCES grupo (id)');
        $this->addSql('ALTER TABLE partido ADD CONSTRAINT FK_4E79750B88774E73 FOREIGN KEY (equipo_local_id) REFERENCES equipo (id)');
        $this->addSql('ALTER TABLE partido ADD CONSTRAINT FK_4E79750B8C243011 FOREIGN KEY (equipo_visitante_id) REFERENCES equipo (id)');
        $this->addSql('ALTER TABLE partido_config ADD CONSTRAINT FK_FB58ADC711856EB4 FOREIGN KEY (partido_id) REFERENCES partido (id)');
        $this->addSql('ALTER TABLE partido_config ADD CONSTRAINT FK_FB58ADC783ECE76 FOREIGN KEY (grupo_equipo1_id) REFERENCES grupo (id)');
        $this->addSql('ALTER TABLE partido_config ADD CONSTRAINT FK_FB58ADC71A8B6198 FOREIGN KEY (grupo_equipo2_id) REFERENCES grupo (id)');
        $this->addSql('ALTER TABLE partido_config ADD CONSTRAINT FK_FB58ADC7A1716C4E FOREIGN KEY (ganador_partido1_id) REFERENCES partido (id)');
        $this->addSql('ALTER TABLE partido_config ADD CONSTRAINT FK_FB58ADC7B3C4C3A0 FOREIGN KEY (ganador_partido2_id) REFERENCES partido (id)');
        $this->addSql('ALTER TABLE sede ADD CONSTRAINT FK_2A9BE2D1A0139802 FOREIGN KEY (torneo_id) REFERENCES torneo (id)');
        $this->addSql('ALTER TABLE torneo ADD CONSTRAINT FK_7CEB63FE62F40C3D FOREIGN KEY (creador_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE torneo_usuario ADD CONSTRAINT FK_4633E7AFA0139802 FOREIGN KEY (torneo_id) REFERENCES torneo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE torneo_usuario ADD CONSTRAINT FK_4633E7AFDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cancha DROP FOREIGN KEY FK_9D09C782E19F41BF');
        $this->addSql('ALTER TABLE categoria DROP FOREIGN KEY FK_4E10122DA0139802');
        $this->addSql('ALTER TABLE equipo DROP FOREIGN KEY FK_C49C530B3397707A');
        $this->addSql('ALTER TABLE equipo DROP FOREIGN KEY FK_C49C530B9C833003');
        $this->addSql('ALTER TABLE grupo DROP FOREIGN KEY FK_8C0E9BD33397707A');
        $this->addSql('ALTER TABLE jugador DROP FOREIGN KEY FK_527D6F1823BFBED');
        $this->addSql('ALTER TABLE partido DROP FOREIGN KEY FK_4E79750B7997F36E');
        $this->addSql('ALTER TABLE partido DROP FOREIGN KEY FK_4E79750B3397707A');
        $this->addSql('ALTER TABLE partido DROP FOREIGN KEY FK_4E79750B9C833003');
        $this->addSql('ALTER TABLE partido DROP FOREIGN KEY FK_4E79750B88774E73');
        $this->addSql('ALTER TABLE partido DROP FOREIGN KEY FK_4E79750B8C243011');
        $this->addSql('ALTER TABLE partido_config DROP FOREIGN KEY FK_FB58ADC711856EB4');
        $this->addSql('ALTER TABLE partido_config DROP FOREIGN KEY FK_FB58ADC783ECE76');
        $this->addSql('ALTER TABLE partido_config DROP FOREIGN KEY FK_FB58ADC71A8B6198');
        $this->addSql('ALTER TABLE partido_config DROP FOREIGN KEY FK_FB58ADC7A1716C4E');
        $this->addSql('ALTER TABLE partido_config DROP FOREIGN KEY FK_FB58ADC7B3C4C3A0');
        $this->addSql('ALTER TABLE sede DROP FOREIGN KEY FK_2A9BE2D1A0139802');
        $this->addSql('ALTER TABLE torneo DROP FOREIGN KEY FK_7CEB63FE62F40C3D');
        $this->addSql('ALTER TABLE torneo_usuario DROP FOREIGN KEY FK_4633E7AFA0139802');
        $this->addSql('ALTER TABLE torneo_usuario DROP FOREIGN KEY FK_4633E7AFDB38439E');
        $this->addSql('DROP TABLE cancha');
        $this->addSql('DROP TABLE categoria');
        $this->addSql('DROP TABLE equipo');
        $this->addSql('DROP TABLE grupo');
        $this->addSql('DROP TABLE jugador');
        $this->addSql('DROP TABLE partido');
        $this->addSql('DROP TABLE partido_config');
        $this->addSql('DROP TABLE sede');
        $this->addSql('DROP TABLE torneo');
        $this->addSql('DROP TABLE torneo_usuario');
        $this->addSql('DROP TABLE usuario');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
