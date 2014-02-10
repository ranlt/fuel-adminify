<?php

return array(
    'migration' => array(
        'list' => 'List of migrations',
        'empty' => 'No migration',
        'version' => 'Version',
        'name' => 'Name',
        'action' => 'Actions',

        'rollback' => 'Rollback to this version',
        'migrate' => 'Migrate to this version',
        'conflict' => 'Conflict between config file and migration table',

        'message' => array(
            'success' => array(
                'app' => array(
                    'current' => 'Project has migrate on the current migration version',
                    'latest' => 'Project has migrate on the latest migration version',
                ),
                'current' => 'Migration :type :name is now on the current version',
                'latest' => 'Migration :type :name is now on the latest version',
                'version' => 'Migration :type :name is now on the version :version',
            ),
        ),
    ),
    'migrate' => array(
        'all' => 'Migrate all',
        'all_current' => 'Migrate all on the current version',
        'current' => 'Migrate all on the current version',
    )
);
