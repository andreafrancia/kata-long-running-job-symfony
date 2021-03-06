<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220526180215 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("ALTER TABLE job ADD COLUMN status VARCHAR(255) NOT NULL DEFAULT 'started'");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE job DROP COLUMN "status"');
    }
}
