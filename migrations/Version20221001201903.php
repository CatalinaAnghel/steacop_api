<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221001201903 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE specialization (id INT AUTO_INCREMENT NOT NULL, department_id INT NOT NULL, name VARCHAR(64) NOT NULL, INDEX IDX_9ED9F26AAE80F5DF (department_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student (id INT AUTO_INCREMENT NOT NULL, specialization_id INT NOT NULL, user_id INT NOT NULL, first_name VARCHAR(64) NOT NULL, last_name VARCHAR(64) NOT NULL, phone_number VARCHAR(10) DEFAULT NULL, INDEX IDX_B723AF33FA846217 (specialization_id), UNIQUE INDEX UNIQ_B723AF33A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE supervisor (id INT AUTO_INCREMENT NOT NULL, department_id INT NOT NULL, user_id INT NOT NULL, description VARCHAR(255) DEFAULT NULL, first_name VARCHAR(64) NOT NULL, last_name VARCHAR(64) NOT NULL, phone_number VARCHAR(10) DEFAULT NULL, INDEX IDX_4D9192F8AE80F5DF (department_id), UNIQUE INDEX UNIQ_4D9192F8A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE specialization ADD CONSTRAINT FK_9ED9F26AAE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33FA846217 FOREIGN KEY (specialization_id) REFERENCES specialization (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE supervisor ADD CONSTRAINT FK_4D9192F8AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE supervisor ADD CONSTRAINT FK_4D9192F8A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user ADD discriminator VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE specialization DROP FOREIGN KEY FK_9ED9F26AAE80F5DF');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33FA846217');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33A76ED395');
        $this->addSql('ALTER TABLE supervisor DROP FOREIGN KEY FK_4D9192F8AE80F5DF');
        $this->addSql('ALTER TABLE supervisor DROP FOREIGN KEY FK_4D9192F8A76ED395');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE specialization');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE supervisor');
        $this->addSql('ALTER TABLE `user` DROP discriminator');
    }
}
