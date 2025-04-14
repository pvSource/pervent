<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250414093059 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE work (id SERIAL NOT NULL, contest_id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_534E68801CD0F0DE ON work (contest_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE work ADD CONSTRAINT FK_534E68801CD0F0DE FOREIGN KEY (contest_id) REFERENCES contest (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX contest_code_key RENAME TO UNIQ_1A95CB577153098
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE work DROP CONSTRAINT FK_534E68801CD0F0DE
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE work
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX uniq_1a95cb577153098 RENAME TO contest_code_key
        SQL);
    }
}
