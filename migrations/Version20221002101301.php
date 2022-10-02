<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221002101301 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE assignment (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, due_date DATETIME DEFAULT NULL, turned_in_date DATETIME DEFAULT NULL, title VARCHAR(128) NOT NULL, description LONGTEXT DEFAULT NULL, grade DOUBLE PRECISION DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, INDEX IDX_30C544BA166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, assignment_id INT NOT NULL, path VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_D8698A76D19302F8 (assignment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE functionality (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, parent_functionality_id INT DEFAULT NULL, type_id INT NOT NULL, functionality_status_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, due_date DATETIME DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, INDEX IDX_F83C5F44166D1F9C (project_id), INDEX IDX_F83C5F44B702AFFB (parent_functionality_id), INDEX IDX_F83C5F44C54C8C93 (type_id), INDEX IDX_F83C5F449922BA51 (functionality_status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE functionality_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE functionality_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE guidance_meeting (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, description VARCHAR(255) DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, is_completed TINYINT(1) NOT NULL, scheduled_at DATETIME NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, INDEX IDX_8817A3CD166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE milestone_meeting (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, description VARCHAR(255) DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, is_completed TINYINT(1) NOT NULL, scheduled_at DATETIME NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, grade DOUBLE PRECISION DEFAULT NULL, INDEX IDX_9DC06C81166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, supervisor_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, repository_url LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_2FB3D0EE19E9AC5F (supervisor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, meeting_id INT NOT NULL, value DOUBLE PRECISION NOT NULL, INDEX IDX_D8892622A76ED395 (user_id), INDEX IDX_D889262267433D9C (meeting_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE supervisory_plan (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(32) NOT NULL, has_low_structure TINYINT(1) NOT NULL, has_low_support TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE system_module (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE system_variable (id INT AUTO_INCREMENT NOT NULL, module_id INT NOT NULL, name VARCHAR(64) NOT NULL, description VARCHAR(128) DEFAULT NULL, value VARCHAR(64) NOT NULL, is_editable TINYINT(1) NOT NULL, INDEX IDX_A5A7360FAFC2B591 (module_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE assignment ADD CONSTRAINT FK_30C544BA166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76D19302F8 FOREIGN KEY (assignment_id) REFERENCES assignment (id)');
        $this->addSql('ALTER TABLE functionality ADD CONSTRAINT FK_F83C5F44166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE functionality ADD CONSTRAINT FK_F83C5F44B702AFFB FOREIGN KEY (parent_functionality_id) REFERENCES functionality (id)');
        $this->addSql('ALTER TABLE functionality ADD CONSTRAINT FK_F83C5F44C54C8C93 FOREIGN KEY (type_id) REFERENCES functionality_type (id)');
        $this->addSql('ALTER TABLE functionality ADD CONSTRAINT FK_F83C5F449922BA51 FOREIGN KEY (functionality_status_id) REFERENCES functionality_status (id)');
        $this->addSql('ALTER TABLE guidance_meeting ADD CONSTRAINT FK_8817A3CD166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE milestone_meeting ADD CONSTRAINT FK_9DC06C81166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE19E9AC5F FOREIGN KEY (supervisor_id) REFERENCES supervisor (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D889262267433D9C FOREIGN KEY (meeting_id) REFERENCES guidance_meeting (id)');
        $this->addSql('ALTER TABLE system_variable ADD CONSTRAINT FK_A5A7360FAFC2B591 FOREIGN KEY (module_id) REFERENCES system_module (id)');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33A76ED395');
        $this->addSql('DROP INDEX UNIQ_B723AF33A76ED395 ON student');
        $this->addSql('ALTER TABLE student ADD supervisory_plan_id INT NOT NULL, CHANGE id id INT NOT NULL, CHANGE user_id project_id INT NOT NULL');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33F908EB9B FOREIGN KEY (supervisory_plan_id) REFERENCES supervisory_plan (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33BF396750 FOREIGN KEY (id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_B723AF33166D1F9C ON student (project_id)');
        $this->addSql('CREATE INDEX IDX_B723AF33F908EB9B ON student (supervisory_plan_id)');
        $this->addSql('ALTER TABLE supervisor DROP FOREIGN KEY FK_4D9192F8A76ED395');
        $this->addSql('DROP INDEX UNIQ_4D9192F8A76ED395 ON supervisor');
        $this->addSql('ALTER TABLE supervisor CHANGE id id INT NOT NULL, CHANGE user_id supervisory_plan_id INT NOT NULL');
        $this->addSql('ALTER TABLE supervisor ADD CONSTRAINT FK_4D9192F8F908EB9B FOREIGN KEY (supervisory_plan_id) REFERENCES supervisory_plan (id)');
        $this->addSql('ALTER TABLE supervisor ADD CONSTRAINT FK_4D9192F8BF396750 FOREIGN KEY (id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_4D9192F8F908EB9B ON supervisor (supervisory_plan_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33166D1F9C');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33F908EB9B');
        $this->addSql('ALTER TABLE supervisor DROP FOREIGN KEY FK_4D9192F8F908EB9B');
        $this->addSql('ALTER TABLE assignment DROP FOREIGN KEY FK_30C544BA166D1F9C');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76D19302F8');
        $this->addSql('ALTER TABLE functionality DROP FOREIGN KEY FK_F83C5F44166D1F9C');
        $this->addSql('ALTER TABLE functionality DROP FOREIGN KEY FK_F83C5F44B702AFFB');
        $this->addSql('ALTER TABLE functionality DROP FOREIGN KEY FK_F83C5F44C54C8C93');
        $this->addSql('ALTER TABLE functionality DROP FOREIGN KEY FK_F83C5F449922BA51');
        $this->addSql('ALTER TABLE guidance_meeting DROP FOREIGN KEY FK_8817A3CD166D1F9C');
        $this->addSql('ALTER TABLE milestone_meeting DROP FOREIGN KEY FK_9DC06C81166D1F9C');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE19E9AC5F');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D8892622A76ED395');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D889262267433D9C');
        $this->addSql('ALTER TABLE system_variable DROP FOREIGN KEY FK_A5A7360FAFC2B591');
        $this->addSql('DROP TABLE assignment');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE functionality');
        $this->addSql('DROP TABLE functionality_status');
        $this->addSql('DROP TABLE functionality_type');
        $this->addSql('DROP TABLE guidance_meeting');
        $this->addSql('DROP TABLE milestone_meeting');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE rating');
        $this->addSql('DROP TABLE supervisory_plan');
        $this->addSql('DROP TABLE system_module');
        $this->addSql('DROP TABLE system_variable');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33BF396750');
        $this->addSql('DROP INDEX IDX_B723AF33166D1F9C ON student');
        $this->addSql('DROP INDEX IDX_B723AF33F908EB9B ON student');
        $this->addSql('ALTER TABLE student ADD user_id INT NOT NULL, DROP project_id, DROP supervisory_plan_id, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B723AF33A76ED395 ON student (user_id)');
        $this->addSql('ALTER TABLE supervisor DROP FOREIGN KEY FK_4D9192F8BF396750');
        $this->addSql('DROP INDEX IDX_4D9192F8F908EB9B ON supervisor');
        $this->addSql('ALTER TABLE supervisor CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE supervisory_plan_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE supervisor ADD CONSTRAINT FK_4D9192F8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4D9192F8A76ED395 ON supervisor (user_id)');
    }
}
