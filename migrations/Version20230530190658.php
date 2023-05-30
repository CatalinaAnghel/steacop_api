<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230530190658 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UC_functionality ON functionality');
        $this->addSql('ALTER TABLE project ADD code VARCHAR(8) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UC_project_code ON project (code)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UC_functionality ON functionality (code)');
        $this->addSql('DROP INDEX UC_project_code ON project');
        $this->addSql('ALTER TABLE project DROP code');
    }
}
