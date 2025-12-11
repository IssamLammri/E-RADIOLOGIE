<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251211092242 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE patient (id INT AUTO_INCREMENT NOT NULL, age INT NOT NULL, gender VARCHAR(50) NOT NULL, history LONGTEXT DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE clinical_case CHANGE patient_age patient_id INT NOT NULL');
        $this->addSql('ALTER TABLE clinical_case ADD CONSTRAINT FK_6AFE4B306B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('CREATE INDEX IDX_6AFE4B306B899279 ON clinical_case (patient_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE patient');
        $this->addSql('ALTER TABLE clinical_case DROP FOREIGN KEY FK_6AFE4B306B899279');
        $this->addSql('DROP INDEX IDX_6AFE4B306B899279 ON clinical_case');
        $this->addSql('ALTER TABLE clinical_case CHANGE patient_id patient_age INT NOT NULL');
    }
}
