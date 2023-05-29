<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230526203318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_functionalities_history (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, status_id INT NOT NULL, number_of_functionalities INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, INDEX IDX_254905BE166D1F9C (project_id), INDEX IDX_254905BE6BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_functionalities_history ADD CONSTRAINT FK_254905BE166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE project_functionalities_history ADD CONSTRAINT FK_254905BE6BF700BD FOREIGN KEY (status_id) REFERENCES functionality_status (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_functionalities_history DROP FOREIGN KEY FK_254905BE166D1F9C');
        $this->addSql('ALTER TABLE project_functionalities_history DROP FOREIGN KEY FK_254905BE6BF700BD');
        $this->addSql('DROP TABLE project_functionalities_history');
    }
}
