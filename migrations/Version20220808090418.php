<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220808090418 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ministere (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, type_id INT NOT NULL, UNIQUE INDEX UNIQ_44118A5BFB88E14F (utilisateur_id), UNIQUE INDEX UNIQ_44118A5BC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ministere ADD CONSTRAINT FK_44118A5BFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ministere ADD CONSTRAINT FK_44118A5BC54C8C93 FOREIGN KEY (type_id) REFERENCES type_ministere (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE ministere');
    }
}
