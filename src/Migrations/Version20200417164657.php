<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200417164657 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE forum_message (id INT AUTO_INCREMENT NOT NULL, topic_id INT NOT NULL, author_id INT NOT NULL, accepted TINYINT(1) DEFAULT \'0\' NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_47717D0E1F55203D (topic_id), INDEX IDX_47717D0EF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_tag (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, position INT DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, color VARCHAR(255) DEFAULT NULL, INDEX IDX_EEA7C17E727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_topic (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, last_message_id INT DEFAULT NULL, name VARCHAR(70) NOT NULL, content LONGTEXT NOT NULL, solved TINYINT(1) DEFAULT \'0\' NOT NULL, sticky TINYINT(1) DEFAULT \'0\' NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, message_count INT DEFAULT 0 NOT NULL, INDEX IDX_853478CCF675F31B (author_id), INDEX IDX_853478CCBA0E79C3 (last_message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_topic_tag (topic_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_E6342771F55203D (topic_id), INDEX IDX_E634277BAD26311 (tag_id), PRIMARY KEY(topic_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE forum_message ADD CONSTRAINT FK_47717D0E1F55203D FOREIGN KEY (topic_id) REFERENCES forum_topic (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE forum_message ADD CONSTRAINT FK_47717D0EF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE forum_tag ADD CONSTRAINT FK_EEA7C17E727ACA70 FOREIGN KEY (parent_id) REFERENCES forum_tag (id)');
        $this->addSql('ALTER TABLE forum_topic ADD CONSTRAINT FK_853478CCF675F31B FOREIGN KEY (author_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE forum_topic ADD CONSTRAINT FK_853478CCBA0E79C3 FOREIGN KEY (last_message_id) REFERENCES forum_message (id)');
        $this->addSql('ALTER TABLE forum_topic_tag ADD CONSTRAINT FK_E6342771F55203D FOREIGN KEY (topic_id) REFERENCES forum_topic (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE forum_topic_tag ADD CONSTRAINT FK_E634277BAD26311 FOREIGN KEY (tag_id) REFERENCES forum_tag (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE forum_topic DROP FOREIGN KEY FK_853478CCBA0E79C3');
        $this->addSql('ALTER TABLE forum_tag DROP FOREIGN KEY FK_EEA7C17E727ACA70');
        $this->addSql('ALTER TABLE forum_topic_tag DROP FOREIGN KEY FK_E634277BAD26311');
        $this->addSql('ALTER TABLE forum_message DROP FOREIGN KEY FK_47717D0E1F55203D');
        $this->addSql('ALTER TABLE forum_topic_tag DROP FOREIGN KEY FK_E6342771F55203D');
        $this->addSql('DROP TABLE forum_message');
        $this->addSql('DROP TABLE forum_tag');
        $this->addSql('DROP TABLE forum_topic');
        $this->addSql('DROP TABLE forum_topic_tag');
    }
}
