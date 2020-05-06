<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200506170048 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_address ADD for_command TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_commands ADD user_address_id INT DEFAULT NULL, ADD sent TINYINT(1) DEFAULT NULL, ADD total_amount DOUBLE PRECISION NOT NULL, ADD sent_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user_commands ADD CONSTRAINT FK_7AB3F72C52D06999 FOREIGN KEY (user_address_id) REFERENCES user_address (id)');
        $this->addSql('CREATE INDEX IDX_7AB3F72C52D06999 ON user_commands (user_address_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_address DROP for_command');
        $this->addSql('ALTER TABLE user_commands DROP FOREIGN KEY FK_7AB3F72C52D06999');
        $this->addSql('DROP INDEX IDX_7AB3F72C52D06999 ON user_commands');
        $this->addSql('ALTER TABLE user_commands DROP user_address_id, DROP sent, DROP total_amount, DROP sent_at');
    }
}
