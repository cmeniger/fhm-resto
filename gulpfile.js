// Include gulp
var gulp = require('gulp');
var sass = require('gulp-sass');
var concat = require('gulp-concat');
var newer = require('gulp-newer');
var uglify = require('gulp-uglify');
var minify = require('gulp-minify-css');
var del = require('del');
// Parameters
var parameters =
{
    js:        {
        watch:      'app/Resources/theme/js/**/*.js',
        src:        ['app/Resources/theme/js/*.js', '!app/Resources/theme/js/**/README.md'],
        dest:       'web/js',
        file:       'main.js',
        concat:     true,
        clean:      true,
        uglify:     false,
        libs:       {
            src:  'app/Resources/theme/js/libs/*.js',
            dest: 'web/js'
        },
        packages:   {
            src:  [
                'app/Resources/theme/packages/**/*.js'
            ],
            dest: 'web/js'
        },
        foundation: {
            src:  [
                'bower_components/foundation/js/foundation.js'
            ],
            dest: 'web/js'
        },
        slick:      {
            src:  [
                'bower_components/slick-carousel/slick/slick.js'
            ],
            dest: 'web/js'
        },
        gmap_cluster:      {
            src:  [
                'bower_components/js-marker-clusterer/src/markerclusterer_compiled.js'
            ],
            dest: 'web/js'
        },
        inview:      {
            src:  [
                'bower_components/inview/inview.js'
            ],
            dest: 'web/js'
        },
        nestable:      {
            src:  [
                'bower_components/nestable2/jquery.nestable.js'
            ],
            dest: 'web/js'
        }
    },
    sass:      {
        watch:    'app/Resources/theme/scss/**/*.scss',
        src:      {
            admin: [
                'app/Resources/theme/scss/admin/import.scss',
                'app/Resources/theme/scss/admin/**/_*.scss',
                'app/Resources/theme/scss/admin/style.scss',
                '!app/Resources/theme/scss/admin/__*.scss',
                '!app/Resources/theme/scss/admin/**/README.md'
            ],
            front: [
                'app/Resources/theme/scss/front/import.scss',
                'app/Resources/theme/scss/front/**/_*.scss',
                'app/Resources/theme/scss/front/style.scss',
                '!app/Resources/theme/scss/front/__*.scss',
                '!app/Resources/theme/scss/front/**/README.md'
            ]
        },
        dest:     'web/css',
        file:     {
            admin: 'admin.scss',
            front: 'front.scss'
        },
        includes: [],
        concat:   true,
        clean:    true,
        minify:   false,
        packages: {
            src:  {
                sass: [
                    'app/Resources/theme/packages/**/*.scss',
                    'app/Resources/theme/packages/**/*.sass'
                ],
                css:  'app/Resources/theme/packages/**/*.css'
            },
            dest: 'web/css'
        }
    },
    images:    {
        watch:    'app/Resources/theme/images/**',
        src:      ['app/Resources/theme/images/**', '!app/Resources/theme/images/**/README.md'],
        dest:     'web/images',
        clean:    true,
        packages: {
            src:  [
                'app/Resources/theme/packages/**/*.gif',
                'app/Resources/theme/packages/**/*.png',
                'app/Resources/theme/packages/**/*.jpg',
                'app/Resources/theme/packages/**/*.svg'
            ],
            dest: 'web/images'
        }
    },
    fonts:     {
        watch:       'app/Resources/theme/fonts/**',
        src:         ['app/Resources/theme/fonts/**', '!app/Resources/theme/fonts/**/README.md'],
        dest:        'web/fonts',
        clean:       true,
        packages:    {
            src:  [
                'app/Resources/theme/packages/**/*.otf',
                'app/Resources/theme/packages/**/*.eot',
                'app/Resources/theme/packages/**/*.svg',
                'app/Resources/theme/packages/**/*.ttf',
                'app/Resources/theme/packages/**/*.woff'
            ],
            dest: 'web/fonts'
        },
        fontawesome: {
            src:  'bower_components/fontawesome/fonts/**',
            dest: 'web/fonts'
        },
        slick:       {
            src:  'bower_components/slick-carousel/slick/fonts/**',
            dest: 'web/fonts'
        }
    },
    libraries: {
        watch:   'app/Resources/theme/libraries/**',
        src:     ['app/Resources/theme/libraries/**', '!app/Resources/theme/libraries/**/README.md'],
        dest:    'web/libraries',
        clean:   true,
        tinymce: {
            js:     {
                src:  'bower_components/tinymce/tinymce.js',
                dest: 'web/libraries/tinymce'
            },
            theme:  {
                src:  'bower_components/tinymce/themes/**',
                dest: 'web/libraries/tinymce/themes'
            },
            skin:   {
                src:  'bower_components/tinymce/skins/**',
                dest: 'web/libraries/tinymce/skins'
            },
            plugin: {
                src:  'bower_components/tinymce/plugins/**',
                dest: 'web/libraries/tinymce/plugins'
            }
        }
    }
};
//----------------------------------------
// TASK - SASS
//----------------------------------------
gulp.task('sass', function ()
{
    var task;
    // Process - Project - Admin
    task = gulp.src(parameters.sass.src.admin);
    task = (parameters.sass.concat) ? task.pipe(concat(parameters.sass.file.admin)) : task;
    task.pipe(sass({includePaths: parameters.sass.includes, errLogToConsole: true}))
        .pipe(gulp.dest(parameters.sass.dest));
    // Process - Project - Front
    task = gulp.src(parameters.sass.src.front);
    task = (parameters.sass.concat) ? task.pipe(concat(parameters.sass.file.front)) : task;
    task.pipe(sass({includePaths: parameters.sass.includes, errLogToConsole: true}))
        .pipe(gulp.dest(parameters.sass.dest));
    // Process - Packages
    gulp.src(parameters.sass.packages.src.sass)
        .pipe(sass({errLogToConsole: true}))
        .pipe(gulp.dest(parameters.sass.packages.dest));
    gulp.src(parameters.sass.packages.src.css)
        .pipe(gulp.dest(parameters.sass.packages.dest));
});
//----------------------------------------
// TASK - SASS PROD
//----------------------------------------
gulp.task('sass-prod', function ()
{
    var process = function ()
    {
        var task;
        // Process - Project - Admin
        task = gulp.src(parameters.sass.src.admin);
        task = (parameters.sass.concat) ? task.pipe(concat(parameters.sass.file.admin)) : task;
        task.pipe(sass({includePaths: parameters.sass.includes, errLogToConsole: true}))
            .pipe(minify({keepSpecialComments: 0}))
            .pipe(gulp.dest(parameters.sass.dest));
        // Process - Project - Front
        task = gulp.src(parameters.sass.src.front);
        task = (parameters.sass.concat) ? task.pipe(concat(parameters.sass.file.front)) : task;
        task.pipe(sass({includePaths: parameters.sass.includes, errLogToConsole: true}))
            .pipe(minify({keepSpecialComments: 0}))
            .pipe(gulp.dest(parameters.sass.dest));
        // Process - Packages
        gulp.src(parameters.sass.packages.src.sass)
            .pipe(sass({errLogToConsole: true}))
            .pipe(minify({keepSpecialComments: 0}))
            .pipe(gulp.dest(parameters.sass.packages.dest));
        gulp.src(parameters.sass.packages.src.css)
            .pipe(minify({keepSpecialComments: 0}))
            .pipe(gulp.dest(parameters.sass.packages.dest));
    };
    // Clean
    if(parameters.sass.clean)
    {
        del(parameters.sass.dest).then(function (e) {
            process();
        });
    }
    else
    {
        process();
    }
});
//----------------------------------------
// TASK - JS
//----------------------------------------
gulp.task('js', function ()
{
    // Process - Project
    var task = gulp.src(parameters.js.src);
    task = (parameters.js.concat) ? task.pipe(concat(parameters.js.file, {newLine: ';'})) : task;
    task.pipe(uglify())
        .pipe(gulp.dest(parameters.js.dest));
    // Process - Libs
    gulp.src(parameters.js.libs.src)
        .pipe(gulp.dest(parameters.js.libs.dest));
    // Process - Packages
    gulp.src(parameters.js.packages.src)
        .pipe(gulp.dest(parameters.js.packages.dest));
    // Process - Foundation
    gulp.src(parameters.js.foundation.src)
        .pipe(gulp.dest(parameters.js.foundation.dest));
    // Process - Slick
    gulp.src(parameters.js.slick.src)
        .pipe(gulp.dest(parameters.js.slick.dest));
    // Process - Gmap Cluster
    gulp.src(parameters.js.gmap_cluster.src)
        .pipe(gulp.dest(parameters.js.gmap_cluster.dest));
    // Process - Inview
    gulp.src(parameters.js.inview.src)
        .pipe(gulp.dest(parameters.js.inview.dest));
    // Process - Nestable
    gulp.src(parameters.js.nestable.src)
        .pipe(gulp.dest(parameters.js.nestable.dest));
});
//----------------------------------------
// TASK - JS PROD
//----------------------------------------
gulp.task('js-prod', function ()
{
    var process = function ()
    {
        // Process - Project
        var task = gulp.src(parameters.js.src);
        task = (parameters.js.concat) ? task.pipe(concat(parameters.js.file, {newLine: ';'})) : task;
        task.pipe(uglify())
            .pipe(gulp.dest(parameters.js.dest));
        // Process - Libs
        gulp.src(parameters.js.libs.src)
            .pipe(uglify())
            .pipe(gulp.dest(parameters.js.libs.dest));
        // Process - Packages
        gulp.src(parameters.js.packages.src)
            .pipe(uglify())
            .pipe(gulp.dest(parameters.js.packages.dest));
        // Process - Foundation
        gulp.src(parameters.js.foundation.src)
            .pipe(uglify())
            .pipe(gulp.dest(parameters.js.foundation.dest));
        // Process - Slick
        gulp.src(parameters.js.slick.src)
            .pipe(uglify())
            .pipe(gulp.dest(parameters.js.slick.dest));
        // Process - Gmap Cluster
        gulp.src(parameters.js.gmap_cluster.src)
            .pipe(uglify())
            .pipe(gulp.dest(parameters.js.gmap_cluster.dest));
        // Process - Inview
        gulp.src(parameters.js.inview.src)
            .pipe(uglify())
            .pipe(gulp.dest(parameters.js.inview.dest));
        // Process - Nestable
        gulp.src(parameters.js.nestable.src)
            .pipe(uglify())
            .pipe(gulp.dest(parameters.js.nestable.dest));
    };
    // Clean
    if(parameters.js.clean)
    {
        del(parameters.js.dest).then(function (e) {
            process();
        });
    }
    else
    {
        process();
    }
});
//----------------------------------------
// TASK - Images
//----------------------------------------
gulp.task('images', function ()
{
    var process = function ()
    {
        // Process
        gulp.src(parameters.images.src)
            .pipe(newer(parameters.images.dest))
            .pipe(gulp.dest(parameters.images.dest));
        // Process - Packages
        gulp.src(parameters.images.packages.src)
            .pipe(newer(parameters.images.packages.dest))
            .pipe(gulp.dest(parameters.images.packages.dest));
    };
    // Clean
    if(parameters.images.clean)
    {
        del(parameters.images.dest).then(function (e) {
            process();
        });
    }
    else
    {
        process();
    }
});
//----------------------------------------
// TASK - Fonts
//----------------------------------------
gulp.task('fonts', function ()
{
    var process = function ()
    {
        // Process - Project
        gulp.src(parameters.fonts.src)
            .pipe(newer(parameters.fonts.dest))
            .pipe(gulp.dest(parameters.fonts.dest));
        // Process - Packages
        gulp.src(parameters.fonts.packages.src)
            .pipe(newer(parameters.fonts.packages.dest))
            .pipe(gulp.dest(parameters.fonts.packages.dest))
        // Process - FontAwesome
        gulp.src(parameters.fonts.fontawesome.src)
            .pipe(newer(parameters.fonts.fontawesome.dest))
            .pipe(gulp.dest(parameters.fonts.fontawesome.dest))
        // Process - Slick
        gulp.src(parameters.fonts.slick.src)
            .pipe(newer(parameters.fonts.slick.dest))
            .pipe(gulp.dest(parameters.fonts.slick.dest))
    };
    // Clean
    if(parameters.fonts.clean)
    {
        del(parameters.fonts.dest).then(function (e) {
            process();
        });
    }
    else
    {
        process();
    }
});
//----------------------------------------
// TASK - Libraries
//----------------------------------------
gulp.task('libraries', function ()
{
    var process = function ()
    {
        // Process - Project
        gulp.src(parameters.libraries.src)
            .pipe(newer(parameters.libraries.dest))
            .pipe(gulp.dest(parameters.libraries.dest));
        // Process - Tinymce
        gulp.src(parameters.libraries.tinymce.js.src)
            .pipe(newer(parameters.libraries.tinymce.js.dest))
            .pipe(gulp.dest(parameters.libraries.tinymce.js.dest));
        gulp.src(parameters.libraries.tinymce.skin.src)
            .pipe(newer(parameters.libraries.tinymce.skin.dest))
            .pipe(gulp.dest(parameters.libraries.tinymce.skin.dest));
        gulp.src(parameters.libraries.tinymce.theme.src)
            .pipe(newer(parameters.libraries.tinymce.theme.dest))
            .pipe(gulp.dest(parameters.libraries.tinymce.theme.dest));
        gulp.src(parameters.libraries.tinymce.plugin.src)
            .pipe(newer(parameters.libraries.tinymce.plugin.dest))
            .pipe(gulp.dest(parameters.libraries.tinymce.plugin.dest));
    };
    // Clean
    if(parameters.libraries.clean)
    {
        del(parameters.libraries.dest).then(function (e) {
            process();
        });
    }
    else
    {
        process();
    }
});
//----------------------------------------
// Watch
//----------------------------------------
gulp.task('watch', ['default'], function ()
{
    // Watch .scss files
    gulp.watch(parameters.sass.watch, ['sass']);
    // Watch .js files
    gulp.watch(parameters.js.watch, ['js']);
    // Watch images files
    gulp.watch(parameters.images.watch, ['images']);
    // Watch fonts files
    gulp.watch(parameters.fonts.watch, ['fonts']);
    // Watch libraries files
    gulp.watch(parameters.fonts.watch, ['libraries']);
});
// Watch .scss files
gulp.task('watch-scss', function ()
{
    gulp.watch(parameters.sass.watch, ['sass']);
});
// Watch .js files
gulp.task('watch-js', function ()
{
    gulp.watch(parameters.js.watch, ['js']);
});
// Watch images files
gulp.task('watch-images', function ()
{
    gulp.watch(parameters.images.watch, ['images']);
});
// Watch fonts files
gulp.task('watch-fonts', function ()
{
    gulp.watch(parameters.fonts.watch, ['fonts']);
});
// Watch libraries files
gulp.task('watch-libraries', function ()
{
    gulp.watch(parameters.libraries.watch, ['libraries']);
});
//----------------------------------------
// Default Task
//----------------------------------------
gulp.task('default', ['sass-prod', 'js-prod', 'images', 'fonts', 'libraries']);