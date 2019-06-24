<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190624114250 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE card (id INT AUTO_INCREMENT NOT NULL, student_id INT NOT NULL, passport_id INT NOT NULL, problem_id INT DEFAULT NULL, modality_id INT DEFAULT NULL, term_id INT DEFAULT NULL, activity_id INT DEFAULT NULL, numbersp INT NOT NULL, entitledsp VARCHAR(255) DEFAULT NULL, infossp VARCHAR(255) DEFAULT NULL, framesp VARCHAR(255) DEFAULT NULL, problemmanagsp VARCHAR(255) DEFAULT NULL, problemcomosp VARCHAR(255) DEFAULT NULL, problemcomwsp VARCHAR(255) DEFAULT NULL, actorssp VARCHAR(255) DEFAULT NULL, targetsp VARCHAR(255) DEFAULT NULL, conditionssp VARCHAR(255) DEFAULT NULL, resourcessp VARCHAR(255) DEFAULT NULL, answerssp VARCHAR(255) DEFAULT NULL, productionssp VARCHAR(255) DEFAULT NULL, writtensp VARCHAR(255) DEFAULT NULL, oralsp VARCHAR(255) DEFAULT NULL, contributionsp LONGTEXT DEFAULT NULL, analysissp LONGTEXT DEFAULT NULL, exist TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, monthsp VARCHAR(255) NOT NULL, yearsp VARCHAR(255) NOT NULL, INDEX IDX_161498D3CB944F1A (student_id), INDEX IDX_161498D3ABF410D0 (passport_id), INDEX IDX_161498D3A0DCED86 (problem_id), INDEX IDX_161498D32D6D889B (modality_id), INDEX IDX_161498D3E2C35FC (term_id), INDEX IDX_161498D381C06096 (activity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, establishment_id INT NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_880E0D76A76ED395 (user_id), UNIQUE INDEX UNIQ_880E0D768565851 (establishment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classroom (id INT AUTO_INCREMENT NOT NULL, establishment_id INT NOT NULL, degree VARCHAR(255) NOT NULL, start_date VARCHAR(255) NOT NULL, end_date VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, exist TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_497D309D8565851 (establishment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, hash VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, exist TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, exist TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE establishment (id INT AUTO_INCREMENT NOT NULL, department_id INT NOT NULL, name VARCHAR(255) NOT NULL, adress VARCHAR(255) NOT NULL, postal_code VARCHAR(5) NOT NULL, city VARCHAR(255) NOT NULL, latitude VARCHAR(255) DEFAULT NULL, longitude VARCHAR(255) DEFAULT NULL, background_url VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, exist TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_DBEFB1EEAE80F5DF (department_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE passport (id INT AUTO_INCREMENT NOT NULL, student_id INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_B5A26E08CB944F1A (student_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, establishment_id INT NOT NULL, classroom_id INT NOT NULL, candidate_nb VARCHAR(255) NOT NULL, birth_date DATETIME NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_B723AF33A76ED395 (user_id), INDEX IDX_B723AF338565851 (establishment_id), INDEX IDX_B723AF336278D5A8 (classroom_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teacher (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, establishment_id INT NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_B0F6A6D5A76ED395 (user_id), INDEX IDX_B0F6A6D58565851 (establishment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teacher_classroom (teacher_id INT NOT NULL, classroom_id INT NOT NULL, INDEX IDX_33167C8641807E1D (teacher_id), INDEX IDX_33167C866278D5A8 (classroom_id), PRIMARY KEY(teacher_id, classroom_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE academy (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, exist TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activity (id INT AUTO_INCREMENT NOT NULL, number VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, skill VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE modality (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE problem (id INT AUTO_INCREMENT NOT NULL, number VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sadmin (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_725824A7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE term (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D3CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D3ABF410D0 FOREIGN KEY (passport_id) REFERENCES passport (id)');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D3A0DCED86 FOREIGN KEY (problem_id) REFERENCES problem (id)');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D32D6D889B FOREIGN KEY (modality_id) REFERENCES modality (id)');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D3E2C35FC FOREIGN KEY (term_id) REFERENCES term (id)');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D381C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE admin ADD CONSTRAINT FK_880E0D76A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE admin ADD CONSTRAINT FK_880E0D768565851 FOREIGN KEY (establishment_id) REFERENCES establishment (id)');
        $this->addSql('ALTER TABLE classroom ADD CONSTRAINT FK_497D309D8565851 FOREIGN KEY (establishment_id) REFERENCES establishment (id)');
        $this->addSql('ALTER TABLE establishment ADD CONSTRAINT FK_DBEFB1EEAE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE passport ADD CONSTRAINT FK_B5A26E08CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF338565851 FOREIGN KEY (establishment_id) REFERENCES establishment (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF336278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id)');
        $this->addSql('ALTER TABLE teacher ADD CONSTRAINT FK_B0F6A6D5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE teacher ADD CONSTRAINT FK_B0F6A6D58565851 FOREIGN KEY (establishment_id) REFERENCES establishment (id)');
        $this->addSql('ALTER TABLE teacher_classroom ADD CONSTRAINT FK_33167C8641807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE teacher_classroom ADD CONSTRAINT FK_33167C866278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sadmin ADD CONSTRAINT FK_725824A7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF336278D5A8');
        $this->addSql('ALTER TABLE teacher_classroom DROP FOREIGN KEY FK_33167C866278D5A8');
        $this->addSql('ALTER TABLE admin DROP FOREIGN KEY FK_880E0D76A76ED395');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33A76ED395');
        $this->addSql('ALTER TABLE teacher DROP FOREIGN KEY FK_B0F6A6D5A76ED395');
        $this->addSql('ALTER TABLE sadmin DROP FOREIGN KEY FK_725824A7A76ED395');
        $this->addSql('ALTER TABLE establishment DROP FOREIGN KEY FK_DBEFB1EEAE80F5DF');
        $this->addSql('ALTER TABLE admin DROP FOREIGN KEY FK_880E0D768565851');
        $this->addSql('ALTER TABLE classroom DROP FOREIGN KEY FK_497D309D8565851');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF338565851');
        $this->addSql('ALTER TABLE teacher DROP FOREIGN KEY FK_B0F6A6D58565851');
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D3ABF410D0');
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D3CB944F1A');
        $this->addSql('ALTER TABLE passport DROP FOREIGN KEY FK_B5A26E08CB944F1A');
        $this->addSql('ALTER TABLE teacher_classroom DROP FOREIGN KEY FK_33167C8641807E1D');
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D381C06096');
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D32D6D889B');
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D3A0DCED86');
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D3E2C35FC');
        $this->addSql('DROP TABLE card');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE classroom');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE establishment');
        $this->addSql('DROP TABLE passport');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE teacher');
        $this->addSql('DROP TABLE teacher_classroom');
        $this->addSql('DROP TABLE academy');
        $this->addSql('DROP TABLE activity');
        $this->addSql('DROP TABLE modality');
        $this->addSql('DROP TABLE problem');
        $this->addSql('DROP TABLE sadmin');
        $this->addSql('DROP TABLE term');
    }
}
