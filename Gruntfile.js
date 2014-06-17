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
		},
		uglify: {
			dist: {
				options: {
					preserveComments: false
				},
				files: {
					'assets/js/nrd.min.js' : 'assets/js/nrd-script.js'
				}
			}
		}

	});

	grunt.registerTask('default',['watch']);
	grunt.registerTask('build', ['uglify']);

};