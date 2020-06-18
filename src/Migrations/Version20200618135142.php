<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200618135142 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_commands ADD billing_address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_commands ADD CONSTRAINT FK_7AB3F72C79D0C0E4 FOREIGN KEY (billing_address_id) REFERENCES user_address (id)');
        $this->addSql('CREATE INDEX IDX_7AB3F72C79D0C0E4 ON user_commands (billing_address_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_commands DROP FOREIGN KEY FK_7AB3F72C79D0C0E4');
        $this->addSql('DROP INDEX IDX_7AB3F72C79D0C0E4 ON user_commands');
        $this->addSql('ALTER TABLE user_commands DROP billing_address_id');
    }
}
