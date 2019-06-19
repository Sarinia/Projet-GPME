<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190523093544 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE establishment (id INT AUTO_INCREMENT NOT NULL, department_id INT NOT NULL, name VARCHAR(255) NOT NULL, adress VARCHAR(255) NOT NULL, postal_code VARCHAR(5) NOT NULL, city VARCHAR(255) NOT NULL, latitude VARCHAR(255) DEFAULT NULL, longitude VARCHAR(255) DEFAULT NULL, background_url VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, exist TINYINT(1) NOT NULL, INDEX IDX_DBEFB1EEAE80F5DF (department_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, establishment_id INT NOT NULL, UNIQUE INDEX UNIQ_880E0D76A76ED395 (user_id), UNIQUE INDEX UNIQ_880E0D768565851 (establishment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classroom (id INT AUTO_INCREMENT NOT NULL, establishment_id INT NOT NULL, degree VARCHAR(255) NOT NULL, start_date VARCHAR(255) NOT NULL, end_date VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, exist TINYINT(1) NOT NULL, INDEX IDX_497D309D8565851 (establishment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, exist TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teacher (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, establishment_id INT NOT NULL, UNIQUE INDEX UNIQ_B0F6A6D5A76ED395 (user_id), INDEX IDX_B0F6A6D58565851 (establishment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE academy (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, exist TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sadmin (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_725824A7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, candidate_nb VARCHAR(255) NOT NULL, birth_date DATETIME NOT NULL, UNIQUE INDEX UNIQ_B723AF33A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, hash VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, exist TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE establishment ADD CONSTRAINT FK_DBEFB1EEAE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE admin ADD CONSTRAINT FK_880E0D76A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE admin ADD CONSTRAINT FK_880E0D768565851 FOREIGN KEY (establishment_id) REFERENCES establishment (id)');
        $this->addSql('ALTER TABLE classroom ADD CONSTRAINT FK_497D309D8565851 FOREIGN KEY (establishment_id) REFERENCES establishment (id)');
        $this->addSql('ALTER TABLE teacher ADD CONSTRAINT FK_B0F6A6D5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE teacher ADD CONSTRAINT FK_B0F6A6D58565851 FOREIGN KEY (establishment_id) REFERENCES establishment (id)');
        $this->addSql('ALTER TABLE sadmin ADD CONSTRAINT FK_725824A7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE admin DROP FOREIGN KEY FK_880E0D768565851');
        $this->addSql('ALTER TABLE classroom DROP FOREIGN KEY FK_497D309D8565851');
        $this->addSql('ALTER TABLE teacher DROP FOREIGN KEY FK_B0F6A6D58565851');
        $this->addSql('ALTER TABLE establishment DROP FOREIGN KEY FK_DBEFB1EEAE80F5DF');
        $this->addSql('ALTER TABLE admin DROP FOREIGN KEY FK_880E0D76A76ED395');
        $this->addSql('ALTER TABLE teacher DROP FOREIGN KEY FK_B0F6A6D5A76ED395');
        $this->addSql('ALTER TABLE sadmin DROP FOREIGN KEY FK_725824A7A76ED395');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33A76ED395');
        $this->addSql('DROP TABLE establishment');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE classroom');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE teacher');
        $this->addSql('DROP TABLE academy');
        $this->addSql('DROP TABLE sadmin');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE user');
    }
}
