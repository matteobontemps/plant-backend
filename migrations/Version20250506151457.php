<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250506151457 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE categorie (id_cat VARCHAR(50) NOT NULL, libelle VARCHAR(255) NOT NULL, idCatParent VARCHAR(50) DEFAULT NULL, INDEX IDX_497DD634F08B125E (idCatParent), PRIMARY KEY(id_cat)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE parcelle (id_parcelle VARCHAR(50) NOT NULL, libelle VARCHAR(255) NOT NULL, longueur INT NOT NULL, largeur INT NOT NULL, taille_carres DOUBLE PRECISION NOT NULL, idUser VARCHAR(50) NOT NULL, INDEX IDX_C56E2CF6FE6E88D7 (idUser), PRIMARY KEY(id_parcelle)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE plante (id_plante VARCHAR(50) NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, idCat VARCHAR(50) DEFAULT NULL, INDEX IDX_517A6947BF165E2F (idCat), PRIMARY KEY(id_plante)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE pousse (id_pousse VARCHAR(50) NOT NULL, x INT DEFAULT NULL, y INT DEFAULT NULL, nb_plants INT DEFAULT NULL, date_plantation DATE DEFAULT NULL, idVariete VARCHAR(50) NOT NULL, idParcelle VARCHAR(50) NOT NULL, INDEX IDX_921E75BB3C6EA6AA (idVariete), INDEX IDX_921E75BB96CD06AD (idParcelle), PRIMARY KEY(id_pousse)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id_user VARCHAR(50) NOT NULL, login VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649AA08CB10 (login), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id_user)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE variete (id_variete VARCHAR(50) NOT NULL, libelle VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, nb_graines INT DEFAULT NULL, ensoleillement LONGTEXT DEFAULT NULL, frequence_arrosage VARCHAR(255) DEFAULT NULL, date_debut_periode_plantation DATE DEFAULT NULL, date_fin_periode_plantation DATE DEFAULT NULL, resistance_froid VARCHAR(255) DEFAULT NULL, temps_avant_recolte VARCHAR(255) DEFAULT NULL, ph VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, idPlante VARCHAR(50) NOT NULL, INDEX IDX_2CD7CD58326BC1DD (idPlante), PRIMARY KEY(id_variete)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE categorie ADD CONSTRAINT FK_497DD634F08B125E FOREIGN KEY (idCatParent) REFERENCES categorie (id_cat) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE parcelle ADD CONSTRAINT FK_C56E2CF6FE6E88D7 FOREIGN KEY (idUser) REFERENCES user (id_user)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE plante ADD CONSTRAINT FK_517A6947BF165E2F FOREIGN KEY (idCat) REFERENCES categorie (id_cat)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pousse ADD CONSTRAINT FK_921E75BB3C6EA6AA FOREIGN KEY (idVariete) REFERENCES variete (id_variete)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pousse ADD CONSTRAINT FK_921E75BB96CD06AD FOREIGN KEY (idParcelle) REFERENCES parcelle (id_parcelle)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE variete ADD CONSTRAINT FK_2CD7CD58326BC1DD FOREIGN KEY (idPlante) REFERENCES plante (id_plante)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE categorie DROP FOREIGN KEY FK_497DD634F08B125E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE parcelle DROP FOREIGN KEY FK_C56E2CF6FE6E88D7
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE plante DROP FOREIGN KEY FK_517A6947BF165E2F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pousse DROP FOREIGN KEY FK_921E75BB3C6EA6AA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pousse DROP FOREIGN KEY FK_921E75BB96CD06AD
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE variete DROP FOREIGN KEY FK_2CD7CD58326BC1DD
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE categorie
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE parcelle
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE plante
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE pousse
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE variete
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
