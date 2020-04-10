<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200410173445 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Добавляем таблицу тегов и таблицу связи тегов и постов';
    }

    public function up(Schema $schema) : void
    {
        // Create 'tag' table
        $table = $schema->createTable('tag');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('name', 'string', ['notnull'=>true, 'length'=>128]);
        $table->setPrimaryKey(['id']);
        $table->addColumn('date_created', 'datetime', ['notnull'=>true,'default'=>date('Y-m-d H:i:s')]);
        $table->addColumn('date_updated', 'datetime', ['notnull'=>true,'default'=>date('Y-m-d H:i:s')]);

        // Create 'post_tag' table
        $table = $schema->createTable('post_tag');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('post_id', 'integer', ['notnull'=>true]);
        $table->addColumn('tag_id', 'integer', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);

        // Add indexes and foreign keys to post_tag table
        $table = $schema->getTable('post_tag');
        $table->addIndex(['post_id'], 'post_tag_post_id_index');
        $table->addIndex(['tag_id'], 'post_tag_id_index');
        $table->addForeignKeyConstraint('post', ['post_id'], ['id'], [], 'post_tag_post_id_fk');
        $table->addForeignKeyConstraint('tag', ['tag_id'], ['id'], [], 'post_tag_tag_id_fk');

    }

    public function down(Schema $schema) : void
    {
        $table = $schema->getTable('tag');
        $table->dropTable('tag');

        $table = $schema->getTable('post_tag');
        $table->dropTable('post_tag');
        $table->dropIndex('post_tag_post_id_index');
        $table->dropIndex('post_tag_id_index');
        $table->removeForeignKey('post_tag_post_id_fk');
        $table->removeForeignKey('post_tag_tag_id_fk');
    }
}
