<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161123150956 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE shop_currency');
        $this->addSql('DROP TABLE shop_language');

        $this->addSql('CREATE TABLE shop_currency (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', shop_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', currency_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', `is_default` TINYINT(1) DEFAULT \'0\' NOT NULL, INDEX IDX_B2EE72A94D16C4DD (shop_id), INDEX IDX_B2EE72A938248176 (currency_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_language (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', shop_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', language_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', `is_default` TINYINT(1) DEFAULT \'0\' NOT NULL, INDEX IDX_F638B234D16C4DD (shop_id), INDEX IDX_F638B2382F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shop_currency ADD CONSTRAINT FK_B2EE72A94D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('ALTER TABLE shop_currency ADD CONSTRAINT FK_B2EE72A938248176 FOREIGN KEY (currency_id) REFERENCES currency (id)');
        $this->addSql('ALTER TABLE shop_language ADD CONSTRAINT FK_F638B234D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('ALTER TABLE shop_language ADD CONSTRAINT FK_F638B2382F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE shop_currency');
        $this->addSql('DROP TABLE shop_language');

        $this->addSql('CREATE TABLE shop_currency (shop_id CHAR(36) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', currency_id CHAR(36) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', INDEX IDX_B2EE72A94D16C4DD (shop_id), INDEX IDX_B2EE72A938248176 (currency_id), PRIMARY KEY(shop_id, currency_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_language (shop_id CHAR(36) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', language_id CHAR(36) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', INDEX IDX_F638B234D16C4DD (shop_id), INDEX IDX_F638B2382F1BAF4 (language_id), PRIMARY KEY(shop_id, language_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shop_currency ADD CONSTRAINT FK_B2EE72A938248176 FOREIGN KEY (currency_id) REFERENCES currency (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_currency ADD CONSTRAINT FK_B2EE72A94D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_language ADD CONSTRAINT FK_F638B234D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_language ADD CONSTRAINT FK_F638B2382F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE');
    }
}
