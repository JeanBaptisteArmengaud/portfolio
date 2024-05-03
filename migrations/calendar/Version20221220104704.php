<?php

declare(strict_types=1);

namespace CalendarMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221220104704 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE `movie` (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, duration VARCHAR(50) NOT NULL COMMENT \'(DC2Type:dateinterval)\', poster VARCHAR(500) DEFAULT NULL, release_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `screen` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, capacity SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE `show` (id INT AUTO_INCREMENT NOT NULL, showtime DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', trailers_duration VARCHAR(50) NOT NULL COMMENT \'(DC2Type:dateinterval)\', presentation_duration VARCHAR(50) NOT NULL COMMENT \'(DC2Type:dateinterval)\', debate_duration VARCHAR(50) NOT NULL COMMENT \'(DC2Type:dateinterval)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `show` ADD movie_id INT NOT NULL, ADD screen_id INT NOT NULL');
        $this->addSql('ALTER TABLE `show` ADD CONSTRAINT FK_320ED9018F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE `show` ADD CONSTRAINT FK_320ED90141A67722 FOREIGN KEY (screen_id) REFERENCES screen (id)');
        $this->addSql('CREATE INDEX IDX_320ED9018F93B6FC ON `show` (movie_id)');
        $this->addSql('CREATE INDEX IDX_320ED90141A67722 ON `show` (screen_id)');


        $this->addSql('CREATE TABLE `screening_schedule` (id INT AUTO_INCREMENT NOT NULL, week_start DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', week_end DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `screening_schedule_movie` (screening_schedule_id INT NOT NULL, movie_id INT NOT NULL, INDEX IDX_FE6B23828250ACC3 (screening_schedule_id), INDEX IDX_FE6B23828F93B6FC (movie_id), PRIMARY KEY(screening_schedule_id, movie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE screening_schedule_movie ADD CONSTRAINT FK_FE6B23828250ACC3 FOREIGN KEY (screening_schedule_id) REFERENCES screening_schedule (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE screening_schedule_movie ADD CONSTRAINT FK_FE6B23828F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');

        $this->addSql('CREATE TABLE `movie_version` (movie_id INT NOT NULL, version_id INT NOT NULL, INDEX IDX_9F8A91ED8F93B6FC (movie_id), INDEX IDX_9F8A91ED4BBC2705 (version_id), PRIMARY KEY(movie_id, version_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `version` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE movie_version ADD CONSTRAINT FK_9F8A91ED8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_version ADD CONSTRAINT FK_9F8A91ED4BBC2705 FOREIGN KEY (version_id) REFERENCES version (id) ON DELETE CASCADE');

        $this->addSql('CREATE TABLE `show_version` (show_id INT NOT NULL, version_id INT NOT NULL, INDEX IDX_D84EFE20D0C1FC64 (show_id), INDEX IDX_D84EFE204BBC2705 (version_id), PRIMARY KEY(show_id, version_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE show_version ADD CONSTRAINT FK_D84EFE20D0C1FC64 FOREIGN KEY (show_id) REFERENCES `show` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE show_version ADD CONSTRAINT FK_D84EFE204BBC2705 FOREIGN KEY (version_id) REFERENCES version (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `show` DROP FOREIGN KEY FK_320ED9018F93B6FC');
        $this->addSql('ALTER TABLE `show` DROP FOREIGN KEY FK_320ED90141A67722');
        $this->addSql('DROP INDEX IDX_320ED9018F93B6FC ON `show`');
        $this->addSql('DROP INDEX IDX_320ED90141A67722 ON `show`');
        $this->addSql('ALTER TABLE `show` DROP movie_id, DROP screen_id');

        $this->addSql('ALTER TABLE screening_schedule_movie DROP FOREIGN KEY FK_FE6B23828250ACC3');
        $this->addSql('ALTER TABLE screening_schedule_movie DROP FOREIGN KEY FK_FE6B23828F93B6FC');

        $this->addSql('ALTER TABLE movie_version DROP FOREIGN KEY FK_9F8A91ED8F93B6FC');
        $this->addSql('ALTER TABLE movie_version DROP FOREIGN KEY FK_9F8A91ED4BBC2705');

        $this->addSql('ALTER TABLE show_version DROP FOREIGN KEY FK_D84EFE20D0C1FC64');
        $this->addSql('ALTER TABLE show_version DROP FOREIGN KEY FK_D84EFE204BBC2705');

        $this->addSql('DROP TABLE show_version');
        $this->addSql('DROP TABLE movie_version');
        $this->addSql('DROP TABLE version');
        $this->addSql('DROP TABLE screening_schedule_movie');
        $this->addSql('DROP TABLE screening_schedule');


        $this->addSql('DROP TABLE `show`');
        $this->addSql('DROP TABLE screen');
        $this->addSql('DROP TABLE movie');

    }
}
