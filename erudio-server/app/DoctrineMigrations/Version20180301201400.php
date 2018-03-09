<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180301201400 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE edu_movimentacao_desligamento ADD unidade_ensino_id INT DEFAULT NULL, CHANGE motivo motivo VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE edu_movimentacao_desligamento ADD CONSTRAINT FK_64B3CC058D0F99DE FOREIGN KEY (unidade_ensino_id) REFERENCES edu_unidade_ensino (id)');
        $this->addSql('CREATE INDEX IDX_64B3CC058D0F99DE ON edu_movimentacao_desligamento (unidade_ensino_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE edu_movimentacao_desligamento DROP FOREIGN KEY FK_64B3CC058D0F99DE');
        $this->addSql('DROP INDEX IDX_64B3CC058D0F99DE ON edu_movimentacao_desligamento');
        $this->addSql('ALTER TABLE edu_movimentacao_desligamento DROP unidade_ensino_id');
    }
}
