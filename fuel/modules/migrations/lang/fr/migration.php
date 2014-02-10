<?php

return array(
    'migration' => array(
        'list' => 'Liste des migrations',
        'empty' => 'Aucune migration',
        'version' => 'Version',
        'name' => 'Nom',
        'action' => 'Actions',

        'rollback' => 'Retourner à cette version',
        'migrate' => 'Migrer cette version',
        'conflict' => 'Conflit entre le fichier config et la table migration',

        'message' => array(
            'success' => array(
                'app' => array(
                    'current' => 'La migration du projet complet est passée sur la version actuelle',
                    'latest' => 'La migration du projet complet est passée sur la dernière version',
                ),
                'current' => 'La migration du :type :name est passée sur la version actuelle',
                'latest' => 'La migration du :type :name est passée sur la dernière version',
                'version' => 'La migration du :type :name est passée à la version :version',
            ),
        ),
    ),
    'migrate' => array(
        'all' => 'Tout migrer',
        'all_current' => 'Tout migrer sur la version actuelle',
        'current' => 'Migrer sur la version actuelle',
    )
);