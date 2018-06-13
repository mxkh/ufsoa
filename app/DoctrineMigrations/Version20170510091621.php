<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170510091621 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE feedback (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', subject_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', customer_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', shop_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', source VARCHAR(150) NOT NULL, name VARCHAR(150) NOT NULL, email VARCHAR(155) DEFAULT NULL, phone VARCHAR(155) DEFAULT NULL, message LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_D229445823EDC87 (subject_id), INDEX IDX_D22944589395C3F3 (customer_id), INDEX IDX_D22944584D16C4DD (shop_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subject (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', shop_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', is_active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_FBCE3E7A4D16C4DD (shop_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subject_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', locale VARCHAR(155) NOT NULL, name VARCHAR(155) DEFAULT NULL, INDEX IDX_2C609D4B2C2AC5D3 (translatable_id), UNIQUE INDEX subject_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D229445823EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D22944589395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D22944584D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('ALTER TABLE subject ADD CONSTRAINT FK_FBCE3E7A4D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('ALTER TABLE subject_translation ADD CONSTRAINT FK_2C609D4B2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES subject (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D229445823EDC87');
        $this->addSql('ALTER TABLE subject_translation DROP FOREIGN KEY FK_2C609D4B2C2AC5D3');
        $this->addSql('DROP TABLE feedback');
        $this->addSql('DROP TABLE subject');
        $this->addSql('DROP TABLE subject_translation');
    }
}
