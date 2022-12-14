<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221213214212 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C444E6803');
        $this->addSql('DROP INDEX IDX_9474526C444E6803 ON comment');
        $this->addSql('ALTER TABLE comment ADD actor_id INT DEFAULT NULL, ADD season_id INT DEFAULT NULL, ADD program_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL, CHANGE episode_id_id episode_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C362B62A0 FOREIGN KEY (episode_id) REFERENCES episode (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C10DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4EC001D1 FOREIGN KEY (season_id) REFERENCES season (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C3EB8070A FOREIGN KEY (program_id) REFERENCES program (id)');
        $this->addSql('CREATE INDEX IDX_9474526C362B62A0 ON comment (episode_id)');
        $this->addSql('CREATE INDEX IDX_9474526C10DAF24A ON comment (actor_id)');
        $this->addSql('CREATE INDEX IDX_9474526C4EC001D1 ON comment (season_id)');
        $this->addSql('CREATE INDEX IDX_9474526C3EB8070A ON comment (program_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C362B62A0');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C10DAF24A');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C4EC001D1');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C3EB8070A');
        $this->addSql('DROP INDEX IDX_9474526C362B62A0 ON comment');
        $this->addSql('DROP INDEX IDX_9474526C10DAF24A ON comment');
        $this->addSql('DROP INDEX IDX_9474526C4EC001D1 ON comment');
        $this->addSql('DROP INDEX IDX_9474526C3EB8070A ON comment');
        $this->addSql('ALTER TABLE comment ADD episode_id_id INT DEFAULT NULL, DROP episode_id, DROP actor_id, DROP season_id, DROP program_id, DROP created_at');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C444E6803 FOREIGN KEY (episode_id_id) REFERENCES episode (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_9474526C444E6803 ON comment (episode_id_id)');
    }
}
