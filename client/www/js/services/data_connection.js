/**
 * Created by langstra on 11-4-15.
 */
angular.module('noob-app')

.service('LocalStorage', function ($window) {
    return {
        /**
         * Set a value on some key
         * @param {string} key - Name of the property
         * @param {mixed} value - Number or string to set
         */
        set: function (key, value) {
            $window.localStorage[key] = value;
        },

        /**
         * Retrieve a value from localstorage by key with the possibility of giving a default value
         * @param {string} key - Property name
         * @param {mixed} defaultValue - Default value when key is not set
         * @returns {mixed}
         */
        get: function (key, defaultValue) {
            return $window.localStorage[key] || defaultValue;
        },

        /**
         * Set an object on some key
         * @param {string} key - Name of the property
         * @param {object} value - Object to set as value
         */
        setObject: function (key, value) {
            $window.localStorage[key] = JSON.stringify(value);
        },

        /**
         * Retrieve an object from localstorage by key with the possibility of giving a default value
         * @param {string} key - Property name
         * @param {mixed} defaultValue - Default value, can be a string or object
         * @returns {object}
         */
        getObject: function (key, defaultValue) {
            defaultValue = typeof defaultValue !== 'undefined' ? defaultValue : '{}';
            var toParse = typeof $window.localStorage[key] !== 'undefined' ? $window.localStorage[key] : defaultValue;
            return JSON.parse( toParse || defaultValue);
        },

        /**
         * Remove an item from localstorage
         * @param {string} key - Propery name of the value to remove
         */
        remove: function (key) {
            $window.localStorage.removeItem(key);
        },

        /**
         * Add key to used keys, for easy deletion of data on logout
         * @param {string} key - Property name
         */
        addToUsedKeys: function (key) {
            var keys = this.getUsedKeys();
            if (keys.usedKeys) {
                if ($.inArray(key, keys.usedKeys)) {
                    keys.usedKeys.push(key);
                    this.setObject('usedKeys', keys);
                }
            } else {
                var usedKeys = [];
                usedKeys.push(key);
                keys.usedKeys = usedKeys;
                this.setObject('usedKeys', keys);
            }
        },

        /**
         * Get all the used keys
         * @returns {Object}
         */
        getUsedKeys: function () {
            return this.getObject('usedKeys');
        },

        /**
         * Remove all the values from localhost and remove the usedKeys property
         */
        removeUsedKeys: function () {
            var keys = this.getUsedKeys();
            for (key in keys.usedKeys) {
                this.remove(keys.usedKeys[key]);
            }
            this.remove('usedKeys');
        },

        /**
         * Get an object and add a key value property to it
         * @param {string} object - Name of the property to add a key value pair to
         * @param {string} key - Key to add to the object
         * @param {mixed} value - Value to add to the object
         */
        addToObject: function (object, key, value) {
            var retrieved = this.getObject(object);
            retrieved[key] = value;
            this.setObject(object, retrieved);
        },

        /**
         * Get value from a key value pair in object
         * @param {string} object - Object to retrieve from localstorage
         * @param {string} key - Key inside the object to read the property value from
         * @param {string} propery - Key inside the key value pairs to read the value from
         * @param {mixed} defaultValue - When the property is not set, this value is returned
         * @returns {mixed}
         */
        getObjectProperty: function (object, key, propery, defaultValue) {
            var retrieved = this.getObject(object);
            if (retrieved[key]) {
                return retrieved[key][propery] || defaultValue;
            } else {
                return defaultValue;
            }
        },

        /**
         * Check if an object contains a specific key
         * @param {string} object - Object key to check if it contains value
         * @param {string} value - Check if value is in the object
         * @returns {boolean}
         */
        objectContainsValue: function (object, value) {
            var retrieved = this.getObject(object, '[]');
            return retrieved.indexOf(value) != -1;
        },

        /**
         * When a JsonRPC request hasn't been sent the query is cached
         * @param {string} method - Method to call
         * @param {object} params - Parameters to go with the call
         */
        addCachedQuery: function (method, params) {
            var key = {method: method, params: params};
            var keys = this.getObject('cachedQuery');
            if (keys.cachedQuery) {
                keys.cachedQuery.push(key);
                this.setObject('cachedQuery', keys);
            } else {
                var cachedQuery = [];
                cachedQuery.push(key);
                keys.cachedQuery = cachedQuery;
                this.setObject('cachedQuery', keys);
            }
        },

        /**
         * Update the project details on on localstorage
         * @param {string} object - Name of the property underneath which the projects are saved
         * @param {string} key - Project id
         * @param {string} data - Named array of properties to be updated in the project
         */
        updateProjectDetails: function (object, key, data) {
            var o = this.getObject(object);
            var k;
            for (k in data) {
                o[key]['projectDetails'][k] = data[k];
            }
            this.setObject(object, o);
        },

        /**
         * Update the report on on localstorage
         * @param {string} object - Name of the property underneath which the reports are saved
         * @param {string} key - Project id
         * @param {string} data - Named array of properties to be updated in the report
         */
        updateReport: function (object, key, data) {
            var o = this.getObject(object);
            var k;
            for (k in data) {
                o[key][k] = data[k];
            }
            this.setObject(object, o);
        },

        clear_all: function() {
            $window.localStorage.clear();
        }

    }
})