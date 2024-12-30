<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240208122244 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categoria (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE comentario (id INT AUTO_INCREMENT NOT NULL, texto VARCHAR(255) DEFAULT NULL, fecha DATE DEFAULT NULL, usuario_id INT NOT NULL, experiencia_id INT NOT NULL, INDEX IDX_4B91E702DB38439E (usuario_id), INDEX IDX_4B91E7027DDD9355 (experiencia_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE comunidad (id INT AUTO_INCREMENT NOT NULL, codigo INT NOT NULL, nombre VARCHAR(80) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE experiencia (id INT AUTO_INCREMENT NOT NULL, titulo VARCHAR(40) NOT NULL, texto VARCHAR(255) NOT NULL, puntuacion INT DEFAULT NULL, fecha DATE DEFAULT NULL, usuario_id INT NOT NULL, localizacion_id INT DEFAULT NULL, subcategoria_id INT DEFAULT NULL, INDEX IDX_DD0E3034DB38439E (usuario_id), INDEX IDX_DD0E3034C851F487 (localizacion_id), INDEX IDX_DD0E303488D3B71A (subcategoria_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE imagen (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(40) DEFAULT NULL, experiencia_id INT DEFAULT NULL, INDEX IDX_8319D2B37DDD9355 (experiencia_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE localizacion (id INT AUTO_INCREMENT NOT NULL, codigo INT NOT NULL, nombre VARCHAR(80) NOT NULL, provincia_id INT NOT NULL, INDEX IDX_5512F0614E7121AF (provincia_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE provincia (id INT AUTO_INCREMENT NOT NULL, codigo INT NOT NULL, nombre VARCHAR(80) NOT NULL, comunidad_id INT NOT NULL, INDEX IDX_D39AF213B824C74B (comunidad_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE rol (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE subcategoria (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(30) NOT NULL, categoria_id INT NOT NULL, INDEX IDX_DA7FB9143397707A (categoria_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(50) NOT NULL, apellidos VARCHAR(50) NOT NULL, mail VARCHAR(60) NOT NULL, ciudad VARCHAR(50) DEFAULT NULL, password VARCHAR(120) NOT NULL, rol_id INT NOT NULL, INDEX IDX_2265B05D4BAB96C (rol_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE comentario ADD CONSTRAINT FK_4B91E702DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE comentario ADD CONSTRAINT FK_4B91E7027DDD9355 FOREIGN KEY (experiencia_id) REFERENCES experiencia (id)');
        $this->addSql('ALTER TABLE experiencia ADD CONSTRAINT FK_DD0E3034DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE experiencia ADD CONSTRAINT FK_DD0E3034C851F487 FOREIGN KEY (localizacion_id) REFERENCES localizacion (id)');
        $this->addSql('ALTER TABLE experiencia ADD CONSTRAINT FK_DD0E303488D3B71A FOREIGN KEY (subcategoria_id) REFERENCES subcategoria (id)');
        $this->addSql('ALTER TABLE imagen ADD CONSTRAINT FK_8319D2B37DDD9355 FOREIGN KEY (experiencia_id) REFERENCES experiencia (id)');
        $this->addSql('ALTER TABLE localizacion ADD CONSTRAINT FK_5512F0614E7121AF FOREIGN KEY (provincia_id) REFERENCES provincia (id)');
        $this->addSql('ALTER TABLE provincia ADD CONSTRAINT FK_D39AF213B824C74B FOREIGN KEY (comunidad_id) REFERENCES comunidad (id)');
        $this->addSql('ALTER TABLE subcategoria ADD CONSTRAINT FK_DA7FB9143397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id)');
        $this->addSql('ALTER TABLE usuario ADD CONSTRAINT FK_2265B05D4BAB96C FOREIGN KEY (rol_id) REFERENCES rol (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comentario DROP FOREIGN KEY FK_4B91E702DB38439E');
        $this->addSql('ALTER TABLE comentario DROP FOREIGN KEY FK_4B91E7027DDD9355');
        $this->addSql('ALTER TABLE experiencia DROP FOREIGN KEY FK_DD0E3034DB38439E');
        $this->addSql('ALTER TABLE experiencia DROP FOREIGN KEY FK_DD0E3034C851F487');
        $this->addSql('ALTER TABLE experiencia DROP FOREIGN KEY FK_DD0E303488D3B71A');
        $this->addSql('ALTER TABLE imagen DROP FOREIGN KEY FK_8319D2B37DDD9355');
        $this->addSql('ALTER TABLE localizacion DROP FOREIGN KEY FK_5512F0614E7121AF');
        $this->addSql('ALTER TABLE provincia DROP FOREIGN KEY FK_D39AF213B824C74B');
        $this->addSql('ALTER TABLE subcategoria DROP FOREIGN KEY FK_DA7FB9143397707A');
        $this->addSql('ALTER TABLE usuario DROP FOREIGN KEY FK_2265B05D4BAB96C');
        $this->addSql('DROP TABLE categoria');
        $this->addSql('DROP TABLE comentario');
        $this->addSql('DROP TABLE comunidad');
        $this->addSql('DROP TABLE experiencia');
        $this->addSql('DROP TABLE imagen');
        $this->addSql('DROP TABLE localizacion');
        $this->addSql('DROP TABLE provincia');
        $this->addSql('DROP TABLE rol');
        $this->addSql('DROP TABLE subcategoria');
        $this->addSql('DROP TABLE usuario');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
