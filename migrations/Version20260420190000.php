<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260420190000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Agrega secuencia de numeracion de partidos por torneo y backfill con maximo actual';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE torneo_partido_secuencia (torneo_id INT NOT NULL, ultimo_numero SMALLINT NOT NULL, PRIMARY KEY(torneo_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE torneo_partido_secuencia ADD CONSTRAINT FK_TORNEO_PARTIDO_SECUENCIA_TORNEO FOREIGN KEY (torneo_id) REFERENCES torneo (id) ON DELETE CASCADE');
        $this->addSql('INSERT INTO torneo_partido_secuencia (torneo_id, ultimo_numero)
            SELECT t.id, COALESCE(MAX(p.numero), 0)
            FROM torneo t
            LEFT JOIN categoria c ON c.torneo_id = t.id
            LEFT JOIN partido p ON p.categoria_id = c.id
            GROUP BY t.id');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE torneo_partido_secuencia DROP FOREIGN KEY FK_TORNEO_PARTIDO_SECUENCIA_TORNEO');
        $this->addSql('DROP TABLE torneo_partido_secuencia');
    }
}
