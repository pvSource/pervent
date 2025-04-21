<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250421172737 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE "like" (id SERIAL NOT NULL, author_id INT NOT NULL, work_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_AC6340B3F675F31B ON "like" (author_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_AC6340B3BB3453DB ON "like" (work_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN "like".created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN "like".deleted_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "like" ADD CONSTRAINT FK_AC6340B3F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "like" ADD CONSTRAINT FK_AC6340B3BB3453DB FOREIGN KEY (work_id) REFERENCES work (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "like" DROP CONSTRAINT FK_AC6340B3F675F31B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "like" DROP CONSTRAINT FK_AC6340B3BB3453DB
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "like"
        SQL);
    }
}
