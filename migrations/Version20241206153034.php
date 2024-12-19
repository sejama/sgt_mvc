<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241206153034 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE grupo ADD categoria_id INT NOT NULL');
        $this->addSql('ALTER TABLE grupo ADD CONSTRAINT FK_8C0E9BD33397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id)');
        $this->addSql('CREATE INDEX IDX_8C0E9BD33397707A ON grupo (categoria_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE grupo DROP FOREIGN KEY FK_8C0E9BD33397707A');
        $this->addSql('DROP INDEX IDX_8C0E9BD33397707A ON grupo');
        $this->addSql('ALTER TABLE grupo DROP categoria_id');
    }
}
