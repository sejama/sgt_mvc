<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241221003916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE partido (id INT AUTO_INCREMENT NOT NULL, cancha_id INT DEFAULT NULL, grupo_id INT NOT NULL, equipo_local_id INT NOT NULL, equipo_visitante_id INT NOT NULL, horario DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', local_set1 SMALLINT DEFAULT NULL, local_set2 SMALLINT DEFAULT NULL, local_set3 SMALLINT DEFAULT NULL, local_set4 SMALLINT DEFAULT NULL, local_set5 SMALLINT DEFAULT NULL, visitante_set1 SMALLINT DEFAULT NULL, visitante_set2 SMALLINT DEFAULT NULL, visitante_set3 SMALLINT DEFAULT NULL, visitante_set4 SMALLINT DEFAULT NULL, visitante_set5 SMALLINT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_4E79750B7997F36E (cancha_id), INDEX IDX_4E79750B9C833003 (grupo_id), INDEX IDX_4E79750B88774E73 (equipo_local_id), INDEX IDX_4E79750B8C243011 (equipo_visitante_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE partido ADD CONSTRAINT FK_4E79750B7997F36E FOREIGN KEY (cancha_id) REFERENCES cancha (id)');
        $this->addSql('ALTER TABLE partido ADD CONSTRAINT FK_4E79750B9C833003 FOREIGN KEY (grupo_id) REFERENCES grupo (id)');
        $this->addSql('ALTER TABLE partido ADD CONSTRAINT FK_4E79750B88774E73 FOREIGN KEY (equipo_local_id) REFERENCES equipo (id)');
        $this->addSql('ALTER TABLE partido ADD CONSTRAINT FK_4E79750B8C243011 FOREIGN KEY (equipo_visitante_id) REFERENCES equipo (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partido DROP FOREIGN KEY FK_4E79750B7997F36E');
        $this->addSql('ALTER TABLE partido DROP FOREIGN KEY FK_4E79750B9C833003');
        $this->addSql('ALTER TABLE partido DROP FOREIGN KEY FK_4E79750B88774E73');
        $this->addSql('ALTER TABLE partido DROP FOREIGN KEY FK_4E79750B8C243011');
        $this->addSql('DROP TABLE partido');
    }
}
