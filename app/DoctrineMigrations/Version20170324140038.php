<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170324140038 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE import ADD count_products INT DEFAULT NULL, ADD count_variants INT DEFAULT NULL, ADD count_departments INT DEFAULT NULL, ADD imported_products INT DEFAULT NULL, ADD imported_variants INT DEFAULT NULL, ADD imported_departments INT DEFAULT NULL, ADD updated_products INT DEFAULT NULL, ADD updated_variants INT DEFAULT NULL, ADD departments_out_of_stock INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product DROP is_active');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE import DROP count_products, DROP count_variants, DROP count_departments, DROP imported_products, DROP imported_variants, DROP imported_departments, DROP updated_products, DROP updated_variants, DROP departments_out_of_stock');
        $this->addSql('ALTER TABLE product ADD is_active TINYINT(1) DEFAULT \'0\' NOT NULL');
    }
}
