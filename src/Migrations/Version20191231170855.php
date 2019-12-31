<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191231170855 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE external_feed_key_word (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, word VARCHAR(255) NOT NULL, count INTEGER NOT NULL, external_feed_id INTEGER DEFAULT NULL)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__external_feed AS SELECT id, source, updated, external_id, body FROM external_feed');
        $this->addSql('DROP TABLE external_feed');
        $this->addSql('CREATE TABLE external_feed (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, source VARCHAR(255) NOT NULL COLLATE BINARY, updated INTEGER NOT NULL, external_id VARCHAR(255) DEFAULT NULL COLLATE BINARY, body CLOB NOT NULL)');
        $this->addSql('INSERT INTO external_feed (id, source, updated, external_id, body) SELECT id, source, updated, external_id, body FROM __temp__external_feed');
        $this->addSql('DROP TABLE __temp__external_feed');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE external_feed_key_word');
        $this->addSql('CREATE TEMPORARY TABLE __temp__external_feed AS SELECT id, source, body, external_id, updated FROM external_feed');
        $this->addSql('DROP TABLE external_feed');
        $this->addSql('CREATE TABLE external_feed (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, source VARCHAR(255) NOT NULL, external_id VARCHAR(255) DEFAULT NULL, updated INTEGER NOT NULL, body CLOB NOT NULL COLLATE BINARY)');
        $this->addSql('INSERT INTO external_feed (id, source, body, external_id, updated) SELECT id, source, body, external_id, updated FROM __temp__external_feed');
        $this->addSql('DROP TABLE __temp__external_feed');
    }
}
