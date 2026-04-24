<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260424190000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Agrega logo opcional a los equipos';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE equipo ADD logo_path VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE equipo DROP logo_path');
    }
}