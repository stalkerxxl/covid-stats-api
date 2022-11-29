<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221129054700 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', continent VARCHAR(255) NOT NULL, population INT NOT NULL, population_density NUMERIC(10, 3) NOT NULL, median_age NUMERIC(5, 2) NOT NULL, aged65_older NUMERIC(5, 3) NOT NULL, aged70_older NUMERIC(5, 3) NOT NULL, gdp_per_capita NUMERIC(10, 3) NOT NULL, diabetes_prevalence NUMERIC(10, 2) NOT NULL, handwashing_facilities NUMERIC(10, 3) NOT NULL, hospital_beds_per_thousand NUMERIC(10, 2) NOT NULL, life_expectancy NUMERIC(10, 2) NOT NULL, new_confirmed INT NOT NULL, total_confirmed INT NOT NULL, new_deaths INT NOT NULL, total_deaths INT NOT NULL, new_recovered INT NOT NULL, total_recovered INT NOT NULL, api_timestamp DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_5373C9665E237E06 (name), UNIQUE INDEX UNIQ_5373C966989D9B62 (slug), UNIQUE INDEX UNIQ_5373C96677153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stat (id INT AUTO_INCREMENT NOT NULL, country_id INT NOT NULL, confirmed INT NOT NULL, deaths INT NOT NULL, recovered INT NOT NULL, api_timestamp DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_20B8FF21F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE stat ADD CONSTRAINT FK_20B8FF21F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stat DROP FOREIGN KEY FK_20B8FF21F92F3E70');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE stat');
    }
}
