<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180709100911 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE app_users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(25) NOT NULL, full_name VARCHAR(256) NOT NULL, password VARCHAR(4096) NOT NULL, email VARCHAR(128) DEFAULT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_C2502824F85E0677 (username), UNIQUE INDEX UNIQ_C2502824E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_roles_mapping (user_id INT NOT NULL, user_role_id INT NOT NULL, INDEX IDX_7A5D527CA76ED395 (user_id), INDEX IDX_7A5D527C8E0E3CA6 (user_role_id), PRIMARY KEY(user_id, user_role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_roles (id INT AUTO_INCREMENT NOT NULL, role_name VARCHAR(128) DEFAULT NULL, UNIQUE INDEX UNIQ_54FCD59FE09C0C92 (role_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users_roles_mapping ADD CONSTRAINT FK_7A5D527CA76ED395 FOREIGN KEY (user_id) REFERENCES app_users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_roles_mapping ADD CONSTRAINT FK_7A5D527C8E0E3CA6 FOREIGN KEY (user_role_id) REFERENCES user_roles (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users_roles_mapping DROP FOREIGN KEY FK_7A5D527CA76ED395');
        $this->addSql('ALTER TABLE users_roles_mapping DROP FOREIGN KEY FK_7A5D527C8E0E3CA6');
        $this->addSql('DROP TABLE app_users');
        $this->addSql('DROP TABLE users_roles_mapping');
        $this->addSql('DROP TABLE user_roles');
    }
}
