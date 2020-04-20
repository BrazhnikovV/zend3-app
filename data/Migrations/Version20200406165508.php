<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200406165508 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $table = $schema->getTable('post');
        $table->addColumn('author_id', 'integer', [
            'notnull' => true,
            'default' => 1
        ]);
        $table->addIndex(['author_id'], 'author_id_index');
        $table->addForeignKeyConstraint('users', ['author_id'], ['id'], [], 'post_users_id_fk');

    }

    public function down(Schema $schema) : void
    {
        $table = $schema->getTable('post');
        $table->dropColumn('author_id');
        $table->dropIndex('author_id_index');
        $table->removeForeignKey('post_users_id_fk');

    }
}
