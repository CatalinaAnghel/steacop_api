<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230219170532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE score_weight ADD weight DOUBLE PRECISION NOT NULL, DROP value');
        $this->addSql('ALTER TABLE supervisory_plan CHANGE number_of_assignments number_of_assignments INT NOT NULL, CHANGE number_of_guidance_meetings number_of_guidance_meetings INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE score_weight ADD value VARCHAR(64) NOT NULL, DROP weight');
        $this->addSql('ALTER TABLE supervisory_plan CHANGE number_of_assignments number_of_assignments INT DEFAULT 0 NOT NULL, CHANGE number_of_guidance_meetings number_of_guidance_meetings INT DEFAULT 0 NOT NULL');
    }
}
