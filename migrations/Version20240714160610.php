<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240714160610 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE answer (id UUID NOT NULL, answer_text TEXT NOT NULL, is_correct BOOLEAN NOT NULL, position INT NOT NULL, question_id UUID DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DADD4A251E27F6BF ON answer (question_id)');
        $this->addSql('CREATE TABLE question (id UUID NOT NULL, question_text TEXT NOT NULL, position INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE session (id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, finished BOOLEAN NOT NULL, finished_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, total_questions INT NOT NULL, correct_answers INT NOT NULL, incorrect_answers INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE session_answer (position INT NOT NULL, selected BOOLEAN NOT NULL, selected_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, session_id UUID NOT NULL, question_id UUID NOT NULL, answer_id UUID NOT NULL, PRIMARY KEY(session_id, question_id, answer_id))');
        $this->addSql('CREATE INDEX IDX_B5B6407C613FECDF ON session_answer (session_id)');
        $this->addSql('CREATE INDEX IDX_B5B6407C1E27F6BF ON session_answer (question_id)');
        $this->addSql('CREATE INDEX IDX_B5B6407CAA334807 ON session_answer (answer_id)');
        $this->addSql('CREATE TABLE session_question (position INT NOT NULL, answered BOOLEAN NOT NULL, correct BOOLEAN NOT NULL, session_id UUID NOT NULL, question_id UUID NOT NULL, PRIMARY KEY(session_id, question_id))');
        $this->addSql('CREATE INDEX IDX_3D5B2926613FECDF ON session_question (session_id)');
        $this->addSql('CREATE INDEX IDX_3D5B29261E27F6BF ON session_question (question_id)');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE session_answer ADD CONSTRAINT FK_B5B6407C613FECDF FOREIGN KEY (session_id) REFERENCES session (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE session_answer ADD CONSTRAINT FK_B5B6407C1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE session_answer ADD CONSTRAINT FK_B5B6407CAA334807 FOREIGN KEY (answer_id) REFERENCES answer (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE session_question ADD CONSTRAINT FK_3D5B2926613FECDF FOREIGN KEY (session_id) REFERENCES session (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE session_question ADD CONSTRAINT FK_3D5B29261E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE answer DROP CONSTRAINT FK_DADD4A251E27F6BF');
        $this->addSql('ALTER TABLE session_answer DROP CONSTRAINT FK_B5B6407C613FECDF');
        $this->addSql('ALTER TABLE session_answer DROP CONSTRAINT FK_B5B6407C1E27F6BF');
        $this->addSql('ALTER TABLE session_answer DROP CONSTRAINT FK_B5B6407CAA334807');
        $this->addSql('ALTER TABLE session_question DROP CONSTRAINT FK_3D5B2926613FECDF');
        $this->addSql('ALTER TABLE session_question DROP CONSTRAINT FK_3D5B29261E27F6BF');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE session');
        $this->addSql('DROP TABLE session_answer');
        $this->addSql('DROP TABLE session_question');
    }
}
