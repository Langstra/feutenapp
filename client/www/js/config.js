/**
 * Created by langstra on 11-4-15.
 */
angular.module('config', [])

    .constant(
    'ENV', {
        'apiServerLink': 'http://frisenfeutig.nl/server/', // API link
        'uploadLink': 'http://frisenfeutig.nl/server/files.php',
        'debug': true
    })

    .constant(
    'IMG', {
        'width': 2000,
        'height': 2000
    })