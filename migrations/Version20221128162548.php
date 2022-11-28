<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221128162548 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stat ADD confirmed INT NOT NULL, ADD deaths INT NOT NULL, ADD recovered INT NOT NULL, DROP new_confirmed, DROP total_confirmed, DROP new_deaths, DROP total_deaths, DROP new_recovered, DROP total_recovered');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stat ADD new_confirmed INT NOT NULL, ADD total_confirmed INT NOT NULL, ADD new_deaths INT NOT NULL, ADD total_deaths INT NOT NULL, ADD new_recovered INT NOT NULL, ADD total_recovered INT NOT NULL, DROP confirmed, DROP deaths, DROP recovered');
    }
}
