<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200404153611 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // Создаем таблицу 'post'
        $table = $schema->createTable('post');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('title', 'text', ['notnull'=>true]);
        $table->addColumn('content', 'text', ['notnull'=>true]);
        $table->addColumn('status', 'integer', ['notnull'=>true]);
        $table->addColumn('date_created', 'datetime', [
            'notnull'=>true,
            'default'=> date(DATE_ATOM, mktime(0, 0, 0, 7, 1, 2000))
        ]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['date_created'], 'date_created_index');

        // Создаем таблицу 'comment'
        $table = $schema->createTable('comment');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('post_id', 'integer', ['notnull'=>true]);
        $table->addColumn('content', 'text', ['notnull'=>true]);
        $table->addColumn('author', 'string', ['notnull'=>true, 'lenght'=>128]);
        $table->addColumn('date_created', 'datetime', [
            'notnull'=>true,
            'default'=> date(DATE_ATOM, mktime(0, 0, 0, 7, 1, 2000))
        ]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['post_id'], 'post_id_index');
        $table->addForeignKeyConstraint('post', ['post_id'], ['id'], [], 'comment_post_id_fk');

    }

    public function down(Schema $schema) : void
    {
        $table = $schema->getTable('comment');
        $table->dropIndex('post_id_index');
        $table->removeForeignKey('comment_post_id_fk');

        $table = $schema->getTable('post');
        $table->dropIndex('date_created_index');

    }
}
