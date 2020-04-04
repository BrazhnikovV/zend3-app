<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200403175748 extends AbstractMigration
{
    /**
     * Returns the description of this migration.
     */
    public function getDescription(): string
    {
        $description = 'A migration which creates the `role` and `permission` tables.';
        return $description;
    }

    /**
     * Upgrades the schema to its newer state.
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        // Create 'role' table
        $table = $schema->createTable('role');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('name', 'string', ['notnull'=>true, 'length'=>128]);
        $table->addColumn('description', 'string', ['notnull'=>true, 'length'=>1024]);
        $table->addColumn('date_created', 'datetime', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['name'], 'name_idx');

        // Create 'role_hierarchy' table (contains parent-child relationships between roles)
        $table = $schema->createTable('role_hierarchy');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('parent_role_id', 'integer', ['notnull'=>true]);
        $table->addColumn('child_role_id', 'integer', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addForeignKeyConstraint('role', ['parent_role_id'], ['id'],
            ['onDelete'=>'CASCADE', 'onUpdate'=>'CASCADE'], 'role_role_parent_role_id_fk');
        $table->addForeignKeyConstraint('role', ['child_role_id'], ['id'],
            ['onDelete'=>'CASCADE', 'onUpdate'=>'CASCADE'], 'role_role_child_role_id_fk');

        // Create 'permission' table
        $table = $schema->createTable('permission');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('name', 'string', ['notnull'=>true, 'length'=>128]);
        $table->addColumn('description', 'string', ['notnull'=>true, 'length'=>1024]);
        $table->addColumn('date_created', 'datetime', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['name'], 'permission_name_idx');

        // Create 'role_permission' table
        $table = $schema->createTable('role_permission');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('role_id', 'integer', ['notnull'=>true]);
        $table->addColumn('permission_id', 'integer', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addForeignKeyConstraint('role', ['role_id'], ['id'],
            ['onDelete'=>'CASCADE', 'onUpdate'=>'CASCADE'], 'role_permission_role_id_fk');
        $table->addForeignKeyConstraint('permission', ['permission_id'], ['id'],
            ['onDelete'=>'CASCADE', 'onUpdate'=>'CASCADE'], 'role_permission_permission_id_fk');

        // Create 'user_role' table
        $table = $schema->createTable('user_role');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('user_id', 'integer', ['notnull'=>true]);
        $table->addColumn('role_id', 'integer', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addForeignKeyConstraint('users', ['user_id'], ['id'],
            ['onDelete'=>'CASCADE', 'onUpdate'=>'CASCADE'], 'user_role_user_id_fk');
        $table->addForeignKeyConstraint('role', ['role_id'], ['id'],
            ['onDelete'=>'CASCADE', 'onUpdate'=>'CASCADE'], 'user_role_role_id_fk');
    }

    /**
     * Reverts the schema changes.
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $table = $schema->getTable('user_role');
        $table->dropTable('user_role');
        $table->removeForeignKey('user_role_user_id_fk');
        $table->removeForeignKey('user_role_role_id_fk');

        $table = $schema->getTable('role_permission');
        $table->dropTable('role_permission');
        $table->removeForeignKey('role_permission_role_id_fk');
        $table->removeForeignKey('role_permission_permission_id_fk');

        $table = $schema->getTable('permission');
        $table->dropTable('permission');
        $this->dropIndex('permission_name_idx');

        $table = $schema->dropTable('role');
        $table->dropTable('role');
        $this->dropIndex('name_idx');
    }
}
