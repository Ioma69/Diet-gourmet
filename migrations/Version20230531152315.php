<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230531152315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_880E0D76E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE allergen (id INT AUTO_INCREMENT NOT NULL, allergy LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE diet (id INT AUTO_INCREMENT NOT NULL, type LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, image LONGTEXT NOT NULL, preparation_time INT NOT NULL, cooking_time INT NOT NULL, ingredients VARCHAR(255) NOT NULL, preparation VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, name VARCHAR(50) NOT NULL, firstname VARCHAR(50) NOT NULL, phone VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_recipes (user_id INT NOT NULL, recipe_id INT NOT NULL, INDEX IDX_FB64FCBFA76ED395 (user_id), INDEX IDX_FB64FCBF59D8A214 (recipe_id), PRIMARY KEY(user_id, recipe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_allergens (user_id INT NOT NULL, allergen_id INT NOT NULL, INDEX IDX_67171250A76ED395 (user_id), INDEX IDX_671712506E775A4A (allergen_id), PRIMARY KEY(user_id, allergen_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_diets (user_id INT NOT NULL, diet_id INT NOT NULL, INDEX IDX_C23FF0FEA76ED395 (user_id), INDEX IDX_C23FF0FEE1E13ACE (diet_id), PRIMARY KEY(user_id, diet_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_recipes ADD CONSTRAINT FK_FB64FCBFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_recipes ADD CONSTRAINT FK_FB64FCBF59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_allergens ADD CONSTRAINT FK_67171250A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_allergens ADD CONSTRAINT FK_671712506E775A4A FOREIGN KEY (allergen_id) REFERENCES allergen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_diets ADD CONSTRAINT FK_C23FF0FEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_diets ADD CONSTRAINT FK_C23FF0FEE1E13ACE FOREIGN KEY (diet_id) REFERENCES diet (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_recipes DROP FOREIGN KEY FK_FB64FCBFA76ED395');
        $this->addSql('ALTER TABLE user_recipes DROP FOREIGN KEY FK_FB64FCBF59D8A214');
        $this->addSql('ALTER TABLE user_allergens DROP FOREIGN KEY FK_67171250A76ED395');
        $this->addSql('ALTER TABLE user_allergens DROP FOREIGN KEY FK_671712506E775A4A');
        $this->addSql('ALTER TABLE user_diets DROP FOREIGN KEY FK_C23FF0FEA76ED395');
        $this->addSql('ALTER TABLE user_diets DROP FOREIGN KEY FK_C23FF0FEE1E13ACE');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE allergen');
        $this->addSql('DROP TABLE diet');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_recipes');
        $this->addSql('DROP TABLE user_allergens');
        $this->addSql('DROP TABLE user_diets');
    }
}
