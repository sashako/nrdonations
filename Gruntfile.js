'use strict';

module.exports = function(grunt) {

	require('load-grunt-tasks')(grunt);

	grunt.initConfig({
		compass: {
			dist: {
				options: {
					sassDir: 'assets/sass',
					outputStyle: 'compressed',
					cssDir: 'assets/css'
				}
			}
		},
		watch: {
			css: {
				files: 'assets/sass/*.sass',
				tasks: ['compass']
			}
		}

	});

	grunt.registerTask('default',['watch']);

};