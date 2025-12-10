<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251210162234 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE clinical_case (id INT AUTO_INCREMENT NOT NULL, patient_age INT NOT NULL, symptoms LONGTEXT DEFAULT NULL, images LONGTEXT DEFAULT NULL, image_comment LONGTEXT DEFAULT NULL, conclusion LONGTEXT DEFAULT NULL, exam_id INT NOT NULL, pathology_id INT DEFAULT NULL, INDEX IDX_6AFE4B30578D5E91 (exam_id), INDEX IDX_6AFE4B30CE86795D (pathology_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE exam (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, modality_id INT NOT NULL, INDEX IDX_38BBA6C62D6D889B (modality_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE modality (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE pathology (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, introduction LONGTEXT DEFAULT NULL, positive_diagnosis LONGTEXT DEFAULT NULL, etiological_diagnosis LONGTEXT DEFAULT NULL, evolution_complications LONGTEXT DEFAULT NULL, differential_diagnosis LONGTEXT DEFAULT NULL, conclusion LONGTEXT DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE clinical_case ADD CONSTRAINT FK_6AFE4B30578D5E91 FOREIGN KEY (exam_id) REFERENCES exam (id)');
        $this->addSql('ALTER TABLE clinical_case ADD CONSTRAINT FK_6AFE4B30CE86795D FOREIGN KEY (pathology_id) REFERENCES pathology (id)');
        $this->addSql('ALTER TABLE exam ADD CONSTRAINT FK_38BBA6C62D6D889B FOREIGN KEY (modality_id) REFERENCES modality (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clinical_case DROP FOREIGN KEY FK_6AFE4B30578D5E91');
        $this->addSql('ALTER TABLE clinical_case DROP FOREIGN KEY FK_6AFE4B30CE86795D');
        $this->addSql('ALTER TABLE exam DROP FOREIGN KEY FK_38BBA6C62D6D889B');
        $this->addSql('DROP TABLE clinical_case');
        $this->addSql('DROP TABLE exam');
        $this->addSql('DROP TABLE modality');
        $this->addSql('DROP TABLE pathology');
    }
}
