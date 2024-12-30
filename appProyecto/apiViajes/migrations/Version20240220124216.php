<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240220124216 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comentario DROP FOREIGN KEY FK_4B91E7027DDD9355');
        $this->addSql('ALTER TABLE comentario DROP FOREIGN KEY FK_4B91E702DB38439E');
        $this->addSql('ALTER TABLE comentario ADD CONSTRAINT FK_4B91E7027DDD9355 FOREIGN KEY (experiencia_id) REFERENCES experiencia (id)');
        $this->addSql('ALTER TABLE comentario ADD CONSTRAINT FK_4B91E702DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE experiencia DROP FOREIGN KEY FK_DD0E3034DB38439E');
        $this->addSql('ALTER TABLE experiencia ADD CONSTRAINT FK_DD0E3034DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE imagen CHANGE nombre nombre LONGBLOB NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comentario DROP FOREIGN KEY FK_4B91E702DB38439E');
        $this->addSql('ALTER TABLE comentario DROP FOREIGN KEY FK_4B91E7027DDD9355');
        $this->addSql('ALTER TABLE comentario ADD CONSTRAINT FK_4B91E702DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comentario ADD CONSTRAINT FK_4B91E7027DDD9355 FOREIGN KEY (experiencia_id) REFERENCES experiencia (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE experiencia DROP FOREIGN KEY FK_DD0E3034DB38439E');
        $this->addSql('ALTER TABLE experiencia ADD CONSTRAINT FK_DD0E3034DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE imagen CHANGE nombre nombre LONGBLOB DEFAULT NULL');
    }
}
