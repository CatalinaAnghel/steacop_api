<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230108195333 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33FA846217');
        $this->addSql('ALTER TABLE specialization DROP FOREIGN KEY FK_9ED9F26AAE80F5DF');
        $this->addSql('DROP TABLE specialization');
        $this->addSql('DROP INDEX IDX_B723AF33FA846217 ON student');
        $this->addSql('ALTER TABLE student DROP specialization_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE specialization (id INT AUTO_INCREMENT NOT NULL, department_id INT NOT NULL, name VARCHAR(64) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_9ED9F26AAE80F5DF (department_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE specialization ADD CONSTRAINT FK_9ED9F26AAE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE student ADD specialization_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33FA846217 FOREIGN KEY (specialization_id) REFERENCES specialization (id)');
        $this->addSql('CREATE INDEX IDX_B723AF33FA846217 ON student (specialization_id)');
    }
}
