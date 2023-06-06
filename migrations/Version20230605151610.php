<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230605151610 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_25BF08CECBB142B5 ON allergen');
        $this->addSql('DROP INDEX UNIQ_9DE465208CDE5729 ON diet');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_25BF08CECBB142B5 ON allergen (allergy)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9DE465208CDE5729 ON diet (type)');
    }
}
