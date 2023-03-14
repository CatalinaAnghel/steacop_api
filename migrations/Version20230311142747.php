<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230311142747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE custom_supervisory_plan (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, number_of_assignments INT NOT NULL, number_of_guidance_meetings INT NOT NULL, UNIQUE INDEX UNIQ_D96F95D4166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE custom_supervisory_plan ADD CONSTRAINT FK_D96F95D4166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE custom_supervisory_plan DROP FOREIGN KEY FK_D96F95D4166D1F9C');
        $this->addSql('DROP TABLE custom_supervisory_plan');
    }
}
