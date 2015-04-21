/**
 * Created by langstra on 11-4-15.
 */
angular.module('config', [])

    .constant(
    'ENV', {
        'apiServerLink': 'http://feuten.rienheuver.nl', // API link
        'uploadLink': 'http://feuten.rienheuver.nl/files.php',
        'debug': true
    })

    .constant(
    'IMG', {
        'width': 2000, // API link
        'height': 2000
    })