<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200409174434 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Поле date_update для сущностей: Post, User, Role, Permission';
    }

    public function up(Schema $schema) : void
    {
        $table = $schema->getTable('post');
        $table->addColumn('date_updated', 'datetime', ['notnull'=>true,'default'=>date('Y-m-d H:i:s')]);

        $table = $schema->getTable('users');
        $table->addColumn('date_updated', 'datetime', ['notnull'=>true,'default'=>date('Y-m-d H:i:s')]);

        $table = $schema->getTable('role');
        $table->addColumn('date_updated', 'datetime', ['notnull'=>true,'default'=>date('Y-m-d H:i:s')]);

        $table = $schema->getTable('permission');
        $table->addColumn('date_updated', 'datetime', ['notnull'=>true,'default'=>date('Y-m-d H:i:s')]);
    }

    public function down(Schema $schema) : void
    {
        $table = $schema->getTable('post');
        $table->dropColumn('date_updated');

        $table = $schema->getTable('users');
        $table->dropColumn('date_updated');

        $table = $schema->getTable('role');
        $table->dropColumn('date_updated');

        $table = $schema->getTable('permission');
        $table->dropColumn('date_updated');
    }
}
