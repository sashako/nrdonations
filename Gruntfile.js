'use strict';

module.exports = function(grunt) {

	require('load-grunt-tasks')(grunt);

	grunt.initConfig({
		compass: {
			dist: {
				options: {
					sassDir: 'sass',
					outputStyle: 'compressed',
					cssDir: 'css'
				}
			}
		},
		watch: {
			css: {
				files: 'sass/*.sass',
				tasks: ['compass']
			}
		}

	});

	grunt.registerTask('default',['watch']);

};