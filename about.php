<?php

/**
 * Manager - one, has many mappers
 * + create($type, $row)
 * + createCollection($type, $table)
 * Entity factory - one, extendable
 * + create($type)
 *
 * RowMapper implements IEntityDataAccessor, ISomethingSomething :-P
 *
 *
 * columns:    type, field => column              (Accessor)
 * refs:       type, field => table, column, type (Accessor)
 * related:    type, field => table, column, type (Accessor)
 * entity:     type => class     (EntityFactory)
 * collection: type => class     (CollectionFactory)
 * accessor:   type => class     (AccessorFactory)
 * table:      type => tableName (SelectionFactory)
 */
