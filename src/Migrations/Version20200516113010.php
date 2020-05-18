<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200516113010 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product ADD promo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADD0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADD0C07AFF ON product (promo_id)');
        $this->addSql('ALTER TABLE user_commands ADD promo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_commands ADD CONSTRAINT FK_7AB3F72CD0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id)');
        $this->addSql('CREATE INDEX IDX_7AB3F72CD0C07AFF ON user_commands (promo_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADD0C07AFF');
        $this->addSql('DROP INDEX IDX_D34A04ADD0C07AFF ON product');
        $this->addSql('ALTER TABLE product DROP promo_id');
        $this->addSql('ALTER TABLE user_commands DROP FOREIGN KEY FK_7AB3F72CD0C07AFF');
        $this->addSql('DROP INDEX IDX_7AB3F72CD0C07AFF ON user_commands');
        $this->addSql('ALTER TABLE user_commands DROP promo_id');
    }
}
