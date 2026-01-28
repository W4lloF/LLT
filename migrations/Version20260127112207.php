<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260127112207 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coach CHANGE experiences experiences VARCHAR(100) DEFAULT NULL, CHANGE nationality nationality VARCHAR(50) DEFAULT NULL, CHANGE languages languages VARCHAR(100) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3F596DCCE7927C74 ON coach (email)');
        $this->addSql('ALTER TABLE subscription CHANGE start_date start_date DATE NOT NULL, CHANGE end_date end_date DATE NOT NULL');
        $this->addSql('ALTER TABLE user ADD roles JSON NOT NULL, DROP role, CHANGE password password VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_3F596DCCE7927C74 ON coach');
        $this->addSql('ALTER TABLE coach CHANGE experiences experiences VARCHAR(100) DEFAULT \'NULL\', CHANGE nationality nationality VARCHAR(50) DEFAULT \'NULL\', CHANGE languages languages VARCHAR(100) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE subscription CHANGE start_date start_date DATE DEFAULT \'NULL\', CHANGE end_date end_date DATE DEFAULT \'NULL\'');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
        $this->addSql('ALTER TABLE user ADD role VARCHAR(50) NOT NULL, DROP roles, CHANGE password password VARCHAR(50) NOT NULL');
    }
}
