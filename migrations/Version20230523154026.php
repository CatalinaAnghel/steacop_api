<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230523154026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE functionality_status_history (id INT AUTO_INCREMENT NOT NULL, functionality_id INT NOT NULL, old_status_id INT NOT NULL, new_status_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_1D49F70A39EDDC8 (functionality_id), INDEX IDX_1D49F70A2E43440C (old_status_id), INDEX IDX_1D49F70A596805D2 (new_status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE functionality_status_history ADD CONSTRAINT FK_1D49F70A39EDDC8 FOREIGN KEY (functionality_id) REFERENCES functionality (id)');
        $this->addSql('ALTER TABLE functionality_status_history ADD CONSTRAINT FK_1D49F70A2E43440C FOREIGN KEY (old_status_id) REFERENCES functionality_status (id)');
        $this->addSql('ALTER TABLE functionality_status_history ADD CONSTRAINT FK_1D49F70A596805D2 FOREIGN KEY (new_status_id) REFERENCES functionality_status (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE functionality_status_history DROP FOREIGN KEY FK_1D49F70A39EDDC8');
        $this->addSql('ALTER TABLE functionality_status_history DROP FOREIGN KEY FK_1D49F70A2E43440C');
        $this->addSql('ALTER TABLE functionality_status_history DROP FOREIGN KEY FK_1D49F70A596805D2');
        $this->addSql('DROP TABLE functionality_status_history');
    }
}
