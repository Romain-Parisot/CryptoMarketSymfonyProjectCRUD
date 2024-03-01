<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240301132442 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE market ADD market_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE market ADD CONSTRAINT FK_6BAC85CB622F3F37 FOREIGN KEY (market_id) REFERENCES market (id)');
        $this->addSql('CREATE INDEX IDX_6BAC85CB622F3F37 ON market (market_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE market DROP FOREIGN KEY FK_6BAC85CB622F3F37');
        $this->addSql('DROP INDEX IDX_6BAC85CB622F3F37 ON market');
        $this->addSql('ALTER TABLE market DROP market_id');
    }
}
