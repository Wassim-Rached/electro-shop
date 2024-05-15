<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240515090333 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, address_line1 VARCHAR(255) NOT NULL, address_line2 VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE application_user (id INT AUTO_INCREMENT NOT NULL, address_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_7A7FBEC1F5B7AF75 (address_id), UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE command (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, for_user_id INT NOT NULL, address_id INT NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivred_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', quantity INT NOT NULL, total INT NOT NULL, INDEX IDX_8ECAEAD44584665A (product_id), INDEX IDX_8ECAEAD49B5BB4B8 (for_user_id), UNIQUE INDEX UNIQ_8ECAEAD4F5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, status VARCHAR(255) NOT NULL, price INT NOT NULL, quantity INT NOT NULL, description LONGTEXT DEFAULT NULL, name VARCHAR(255) NOT NULL, is_used TINYINT(1) NOT NULL, photo VARCHAR(255) NOT NULL, INDEX IDX_D34A04ADB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_report (id INT AUTO_INCREMENT NOT NULL, by_user_id INT NOT NULL, to_product_id INT NOT NULL, description LONGTEXT DEFAULT NULL, reason VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_A6533620DC9C2434 (by_user_id), INDEX IDX_A653362062B78427 (to_product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE application_user ADD CONSTRAINT FK_7A7FBEC1F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD44584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD49B5BB4B8 FOREIGN KEY (for_user_id) REFERENCES application_user (id)');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD4F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADB03A8386 FOREIGN KEY (created_by_id) REFERENCES application_user (id)');
        $this->addSql('ALTER TABLE product_report ADD CONSTRAINT FK_A6533620DC9C2434 FOREIGN KEY (by_user_id) REFERENCES application_user (id)');
        $this->addSql('ALTER TABLE product_report ADD CONSTRAINT FK_A653362062B78427 FOREIGN KEY (to_product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE application_user DROP FOREIGN KEY FK_7A7FBEC1F5B7AF75');
        $this->addSql('ALTER TABLE command DROP FOREIGN KEY FK_8ECAEAD44584665A');
        $this->addSql('ALTER TABLE command DROP FOREIGN KEY FK_8ECAEAD49B5BB4B8');
        $this->addSql('ALTER TABLE command DROP FOREIGN KEY FK_8ECAEAD4F5B7AF75');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADB03A8386');
        $this->addSql('ALTER TABLE product_report DROP FOREIGN KEY FK_A6533620DC9C2434');
        $this->addSql('ALTER TABLE product_report DROP FOREIGN KEY FK_A653362062B78427');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE application_user');
        $this->addSql('DROP TABLE command');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_report');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
