module.exports = function (grunt) {
    grunt.loadNpmTasks('gruntify-eslint');
    require('load-grunt-tasks')(grunt);
    var pkg = grunt.file.readJSON('package.json');
    var bannerTemplate = '/**\n' + ' * <%= pkg.title %> - v<%= pkg.version %> - <%= grunt.template.today("yyyy-mm-dd") %>\n' + ' * <%= pkg.author.url %>\n' + ' *\n' + ' * Copyright (c) <%= grunt.template.today("yyyy") %>;\n' + ' * Licensed GPLv2+\n' + ' */\n';
    var compactBannerTemplate = '/** ' + '<%= pkg.title %> - v<%= pkg.version %> - <%= grunt.template.today("yyyy-mm-dd") %> | <%= pkg.author.url %> | Copyright (c) <%= grunt.template.today("yyyy") %>; | Licensed GPLv2+' + ' **/\n';
    // Project configuration
    grunt.initConfig({
        pkg: pkg,
        watch: {
            styles: {
                files: [
                    'assets/**/*.css',
                    'assets/**/*.scss'
                ],
                tasks: ['styles'],
                options: {
                    spawn: false,
                    livereload: true,
                    debounceDelay: 500
                }
            },
            scripts: {
                files: ['assets/**/*.js'],
                tasks: ['scripts'],
                options: {
                    spawn: false,
                    livereload: true,
                    debounceDelay: 500
                }
            },
            php: {
                files: [
                    '**/*.php',
                    '!vendor/**.*.php'
                ],
                tasks: ['php'],
                options: {
                    spawn: false,
                    debounceDelay: 500
                }
            }
        },
        makepot: {
            dist: {
                options: {
                    domainPath: '/languages/',
                    potFilename: pkg.name + '.pot',
                    type: 'wp-plugin'
                }
            }
        },
        addtextdomain: {
            dist: {
                options: { textdomain: pkg.name },
                target: { files: { src: ['**/*.php'] } }
            }
        },
        eslint: {
            src: [
                'assets/js/components/**/*.js',
                '!**/*.min.js'
            ]
        },
        browserify: {
            options: {
                stripBanners: true,
                banner: bannerTemplate,
                transform: [
                    [
                        'babelify',
                        { presets: ['es2015'] }
                    ],
                    'ejsify',
                    'browserify-shim'
                ]
            },
            dist: { files: { 'assets/js/admin-shortcode.js': 'assets/js/components/admin-shortcode.js' } }
        },
        uglify: {
            dist: {
                files: { 'assets/js/admin-shortcode.min.js': 'assets/js/admin-shortcode.js' },
                options: { banner: compactBannerTemplate }
            }
        },
        sass: {
            dist: {
                options: { sourceMap: true },
                files: { 'assets/css/shopify-buy-button.css': 'assets/css/sass/styles.scss' }
            }
        },
        cssmin: { dist: { files: { 'assets/css/shopify-buy-button.min.css': 'assets/css/shopify-buy-button.css' } } },
        usebanner: {
            taskName: {
                options: {
                    position: 'top',
                    banner: bannerTemplate,
                    linebreak: true
                },
                files: { src: ['assets/css/shopify-buy-button.min.css'] }
            }
        }
    });
    // Default task.
    grunt.registerTask('scripts', [
        'eslint',
        'browserify',
        'uglify'
    ]);
    grunt.registerTask('styles', [
        'sass',
        'cssmin',
        'usebanner'
    ]);
    grunt.registerTask('php', [
        'addtextdomain',
        'makepot'
    ]);
    grunt.registerTask('default', [
        'styles',
        'scripts',
        'php'
    ]);
    grunt.util.linefeed = '\n';
};