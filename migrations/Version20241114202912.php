<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241114202912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7CEB63FE3A909126 ON torneo (nombre)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7CEB63FEC3AEF08C ON torneo (ruta)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_7CEB63FE3A909126 ON torneo');
        $this->addSql('DROP INDEX UNIQ_7CEB63FEC3AEF08C ON torneo');
    }
}
