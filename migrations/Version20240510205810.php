<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240510205810 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE application_user DROP FOREIGN KEY FK_7A7FBEC11623CB0A');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE command DROP FOREIGN KEY FK_8ECAEAD44C3A3BB');
        $this->addSql('DROP TABLE user_verification');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE payment_detail');
        $this->addSql('ALTER TABLE address DROP country, CHANGE address_line2 address_line2 VARCHAR(255) DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_7A7FBEC11623CB0A ON application_user');
        $this->addSql('ALTER TABLE application_user DROP verification_id, DROP first_name, DROP last_name, DROP is_banned');
        $this->addSql('ALTER TABLE command DROP FOREIGN KEY FK_8ECAEAD4DC9C2434');
        $this->addSql('DROP INDEX IDX_8ECAEAD4DC9C2434 ON command');
        $this->addSql('DROP INDEX UNIQ_8ECAEAD44C3A3BB ON command');
        $this->addSql('ALTER TABLE command DROP payment_id, CHANGE total total INT NOT NULL, CHANGE by_user_id for_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD49B5BB4B8 FOREIGN KEY (for_user_id) REFERENCES application_user (id)');
        $this->addSql('CREATE INDEX IDX_8ECAEAD49B5BB4B8 ON command (for_user_id)');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD5B075477');
        $this->addSql('DROP INDEX IDX_D34A04AD5B075477 ON product');
        $this->addSql('DROP INDEX IDX_D34A04AD12469DE2 ON product');
        $this->addSql('ALTER TABLE product ADD created_by_id INT NOT NULL, DROP category_id, DROP published_by_id, DROP photo, DROP is_banned, CHANGE price price INT NOT NULL, CHANGE description description LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADB03A8386 FOREIGN KEY (created_by_id) REFERENCES application_user (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADB03A8386 ON product (created_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_verification (id INT AUTO_INCREMENT NOT NULL, cin VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, cin_photo VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, person_photo VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE payment_detail (id INT AUTO_INCREMENT NOT NULL, provider VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, status VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, amount DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE address ADD country VARCHAR(255) NOT NULL, CHANGE address_line2 address_line2 VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE application_user ADD verification_id INT DEFAULT NULL, ADD first_name VARCHAR(255) NOT NULL, ADD last_name VARCHAR(255) NOT NULL, ADD is_banned TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE application_user ADD CONSTRAINT FK_7A7FBEC11623CB0A FOREIGN KEY (verification_id) REFERENCES user_verification (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7A7FBEC11623CB0A ON application_user (verification_id)');
        $this->addSql('ALTER TABLE command DROP FOREIGN KEY FK_8ECAEAD49B5BB4B8');
        $this->addSql('DROP INDEX IDX_8ECAEAD49B5BB4B8 ON command');
        $this->addSql('ALTER TABLE command ADD payment_id INT DEFAULT NULL, CHANGE total total DOUBLE PRECISION NOT NULL, CHANGE for_user_id by_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD44C3A3BB FOREIGN KEY (payment_id) REFERENCES payment_detail (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD4DC9C2434 FOREIGN KEY (by_user_id) REFERENCES application_user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8ECAEAD4DC9C2434 ON command (by_user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8ECAEAD44C3A3BB ON command (payment_id)');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADB03A8386');
        $this->addSql('DROP INDEX IDX_D34A04ADB03A8386 ON product');
        $this->addSql('ALTER TABLE product ADD published_by_id INT NOT NULL, ADD photo VARCHAR(255) DEFAULT NULL, ADD is_banned TINYINT(1) DEFAULT NULL, CHANGE price price DOUBLE PRECISION NOT NULL, CHANGE description description LONGTEXT NOT NULL, CHANGE created_by_id category_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD5B075477 FOREIGN KEY (published_by_id) REFERENCES application_user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_D34A04AD5B075477 ON product (published_by_id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD12469DE2 ON product (category_id)');
    }
}
