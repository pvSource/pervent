<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250418171239 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE contest RENAME COLUMN code TO slug
        SQL);

        $this->addSql("UPDATE contest SET slug=CONCAT(LOWER(name), '-', created_at)");
        $this->addSql('ALTER TABLE contest ALTER COLUMN slug SET NOT NULL');

        //$this->addSql('ALTER TABLE contest DROP CONSTRAINT uniq_1a905cb577135098');

        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_1A95CB5989D9B62 ON contest (slug)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_1A95CB5989D9B62
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contest RENAME COLUMN slug TO code
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX uniq_1a95cb577153098 ON contest (code)
        SQL);
    }
}
