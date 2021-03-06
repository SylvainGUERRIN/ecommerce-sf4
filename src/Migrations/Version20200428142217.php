<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200428142217 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_address ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_address ADD CONSTRAINT FK_5543718BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5543718BA76ED395 ON user_address (user_id)');
        $this->addSql('ALTER TABLE user_commands ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_commands ADD CONSTRAINT FK_7AB3F72CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_7AB3F72CA76ED395 ON user_commands (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_address DROP FOREIGN KEY FK_5543718BA76ED395');
        $this->addSql('DROP INDEX IDX_5543718BA76ED395 ON user_address');
        $this->addSql('ALTER TABLE user_address DROP user_id');
        $this->addSql('ALTER TABLE user_commands DROP FOREIGN KEY FK_7AB3F72CA76ED395');
        $this->addSql('DROP INDEX IDX_7AB3F72CA76ED395 ON user_commands');
        $this->addSql('ALTER TABLE user_commands DROP user_id');
    }
}
