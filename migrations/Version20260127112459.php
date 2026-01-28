<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260127112459 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE coach (id INT AUTO_INCREMENT NOT NULL, pseudo VARCHAR(50) NOT NULL, email VARCHAR(100) NOT NULL, type VARCHAR(20) NOT NULL, bio LONGTEXT DEFAULT NULL, experiences VARCHAR(100) DEFAULT NULL, nationality VARCHAR(50) DEFAULT NULL, languages VARCHAR(100) DEFAULT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_3F596DCCE7927C74 (email), UNIQUE INDEX UNIQ_3F596DCCA76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE coaching (id INT AUTO_INCREMENT NOT NULL, datetime DATETIME NOT NULL, coaching_type VARCHAR(50) NOT NULL, status VARCHAR(50) NOT NULL, user_id INT NOT NULL, coach_id INT NOT NULL, INDEX IDX_CABE08CEA76ED395 (user_id), INDEX IDX_CABE08CE3C105691 (coach_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE feedback (id INT AUTO_INCREMENT NOT NULL, text LONGTEXT NOT NULL, creation_date DATE NOT NULL, coach_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_D22944583C105691 (coach_id), INDEX IDX_D2294458A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE subscription (id INT AUTO_INCREMENT NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, status VARCHAR(20) NOT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_A3C664D3A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(50) NOT NULL, email VARCHAR(100) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, registration_date DATE NOT NULL, coin INT NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE videos (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, link VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, access VARCHAR(50) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE videos_user (videos_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_2FBA6ABD763C10B2 (videos_id), INDEX IDX_2FBA6ABDA76ED395 (user_id), PRIMARY KEY (videos_id, user_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE coach ADD CONSTRAINT FK_3F596DCCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE coaching ADD CONSTRAINT FK_CABE08CEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE coaching ADD CONSTRAINT FK_CABE08CE3C105691 FOREIGN KEY (coach_id) REFERENCES coach (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D22944583C105691 FOREIGN KEY (coach_id) REFERENCES coach (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D2294458A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE videos_user ADD CONSTRAINT FK_2FBA6ABD763C10B2 FOREIGN KEY (videos_id) REFERENCES videos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE videos_user ADD CONSTRAINT FK_2FBA6ABDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coach DROP FOREIGN KEY FK_3F596DCCA76ED395');
        $this->addSql('ALTER TABLE coaching DROP FOREIGN KEY FK_CABE08CEA76ED395');
        $this->addSql('ALTER TABLE coaching DROP FOREIGN KEY FK_CABE08CE3C105691');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D22944583C105691');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D2294458A76ED395');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D3A76ED395');
        $this->addSql('ALTER TABLE videos_user DROP FOREIGN KEY FK_2FBA6ABD763C10B2');
        $this->addSql('ALTER TABLE videos_user DROP FOREIGN KEY FK_2FBA6ABDA76ED395');
        $this->addSql('DROP TABLE coach');
        $this->addSql('DROP TABLE coaching');
        $this->addSql('DROP TABLE feedback');
        $this->addSql('DROP TABLE subscription');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE videos');
        $this->addSql('DROP TABLE videos_user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
