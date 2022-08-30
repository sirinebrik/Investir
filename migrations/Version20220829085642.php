<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220829085642 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE discussion (id INT AUTO_INCREMENT NOT NULL, investisseur_id INT DEFAULT NULL, offre_id INT DEFAULT NULL, send VARCHAR(255) NOT NULL, date_envoi VARCHAR(255) NOT NULL, message VARCHAR(255) NOT NULL, INDEX IDX_C0B9F90FA8F9CCCA (investisseur_id), INDEX IDX_C0B9F90F4CC8505A (offre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE investir_offre (id INT AUTO_INCREMENT NOT NULL, investisseur_id INT DEFAULT NULL, offre_id INT DEFAULT NULL, etat VARCHAR(255) NOT NULL, date_investir VARCHAR(255) NOT NULL, INDEX IDX_7DCFDCAEA8F9CCCA (investisseur_id), INDEX IDX_7DCFDCAE4CC8505A (offre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE discussion ADD CONSTRAINT FK_C0B9F90FA8F9CCCA FOREIGN KEY (investisseur_id) REFERENCES investisseur (id)');
        $this->addSql('ALTER TABLE discussion ADD CONSTRAINT FK_C0B9F90F4CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id)');
        $this->addSql('ALTER TABLE investir_offre ADD CONSTRAINT FK_7DCFDCAEA8F9CCCA FOREIGN KEY (investisseur_id) REFERENCES investisseur (id)');
        $this->addSql('ALTER TABLE investir_offre ADD CONSTRAINT FK_7DCFDCAE4CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE discussion');
        $this->addSql('DROP TABLE investir_offre');
    }
}
