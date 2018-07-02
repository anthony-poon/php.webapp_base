<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180702061403 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE user_roles_mapping');
        $this->addSql('ALTER TABLE user_roles ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_roles ADD CONSTRAINT FK_54FCD59FA76ED395 FOREIGN KEY (user_id) REFERENCES app_users (id)');
        $this->addSql('CREATE INDEX IDX_54FCD59FA76ED395 ON user_roles (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_roles_mapping (user_id INT NOT NULL, user_role_id INT NOT NULL, INDEX IDX_9D36F721A76ED395 (user_id), INDEX IDX_9D36F7218E0E3CA6 (user_role_id), PRIMARY KEY(user_id, user_role_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_roles_mapping ADD CONSTRAINT FK_9D36F7218E0E3CA6 FOREIGN KEY (user_role_id) REFERENCES user_roles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_roles_mapping ADD CONSTRAINT FK_9D36F721A76ED395 FOREIGN KEY (user_id) REFERENCES app_users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_roles DROP FOREIGN KEY FK_54FCD59FA76ED395');
        $this->addSql('DROP INDEX IDX_54FCD59FA76ED395 ON user_roles');
        $this->addSql('ALTER TABLE user_roles DROP user_id');
    }
}
