<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241105024104 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cancha (id INT AUTO_INCREMENT NOT NULL, sede_id INT DEFAULT NULL, nombre VARCHAR(255) NOT NULL, descripcion VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9D09C782E19F41BF (sede_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categoria (id INT AUTO_INCREMENT NOT NULL, torneo_id INT DEFAULT NULL, nombre VARCHAR(128) NOT NULL, genero VARCHAR(255) NOT NULL, disputa LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', nombre_corto VARCHAR(8) NOT NULL, INDEX IDX_4E10122DA0139802 (torneo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sede (id INT AUTO_INCREMENT NOT NULL, torneo_id INT DEFAULT NULL, nombre VARCHAR(255) NOT NULL, domicilio VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_2A9BE2D1A0139802 (torneo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE torneo (id INT AUTO_INCREMENT NOT NULL, creador_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, ruta VARCHAR(32) NOT NULL, descripcion VARCHAR(255) DEFAULT NULL, fecha_inicio_inscripcion DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', fecha_fin_inscripcion DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', fecha_inicio_torneo DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', fecha_fin_torneo DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', reglamento LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CEB63FE62F40C3D (creador_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE torneo_user (torneo_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_281C1938A0139802 (torneo_id), INDEX IDX_281C1938A76ED395 (user_id), PRIMARY KEY(torneo_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, email VARCHAR(128) DEFAULT NULL, nombre VARCHAR(128) DEFAULT NULL, apellido VARCHAR(128) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cancha ADD CONSTRAINT FK_9D09C782E19F41BF FOREIGN KEY (sede_id) REFERENCES sede (id)');
        $this->addSql('ALTER TABLE categoria ADD CONSTRAINT FK_4E10122DA0139802 FOREIGN KEY (torneo_id) REFERENCES torneo (id)');
        $this->addSql('ALTER TABLE sede ADD CONSTRAINT FK_2A9BE2D1A0139802 FOREIGN KEY (torneo_id) REFERENCES torneo (id)');
        $this->addSql('ALTER TABLE torneo ADD CONSTRAINT FK_7CEB63FE62F40C3D FOREIGN KEY (creador_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE torneo_user ADD CONSTRAINT FK_281C1938A0139802 FOREIGN KEY (torneo_id) REFERENCES torneo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE torneo_user ADD CONSTRAINT FK_281C1938A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cancha DROP FOREIGN KEY FK_9D09C782E19F41BF');
        $this->addSql('ALTER TABLE categoria DROP FOREIGN KEY FK_4E10122DA0139802');
        $this->addSql('ALTER TABLE sede DROP FOREIGN KEY FK_2A9BE2D1A0139802');
        $this->addSql('ALTER TABLE torneo DROP FOREIGN KEY FK_7CEB63FE62F40C3D');
        $this->addSql('ALTER TABLE torneo_user DROP FOREIGN KEY FK_281C1938A0139802');
        $this->addSql('ALTER TABLE torneo_user DROP FOREIGN KEY FK_281C1938A76ED395');
        $this->addSql('DROP TABLE cancha');
        $this->addSql('DROP TABLE categoria');
        $this->addSql('DROP TABLE sede');
        $this->addSql('DROP TABLE torneo');
        $this->addSql('DROP TABLE torneo_user');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
