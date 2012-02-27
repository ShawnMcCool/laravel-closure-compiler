# Google Closure Compiler Bundle, by Shawn McCool

A Laravel [Closure Compiler](https://developers.google.com/closure/compiler/) bundle, installable via the Artisan CLI:

    php artisan bundle:install closure-compiler

**Important:** Closure Compiler requires that Java is installed.  Consequently, it can run on virtually any operating system.

### Description

This bundle automatically minifies a site's JavaScript files and updates them only when necessary.  List your site's JavaScript files in a config file and when a site runs in the development environment it'll check to see if any of the JavaScript files have been updated since the last minification.  If so, it'll minify immediately and keep itself up to date.  The developer or designer would then simply commit their code into their repository as normal including the minified file.  In production the minification system is never loaded.  The site's layout view should link to the minified file instead of to each of the JavaScript files as their contents are all minified together into the singular output file.

### 1. Bundle Registration

Add 'closure-compiler' to your **application/bundles.php** file:

    return array(
        'closure-compiler'
    );

### 2. Configuration

In the bundle's config file simply verify that your script path (the location in which your javascript files reside) and that your script_output_file is defined. The script_output_file will contain the resulting minified JavaScript.

Then, add the JavaScript files that you'd like to be minified to the 'minify_scripts' array.  An example:

    'minify_scripts' => array(
        // included some examples, replace these with your own
        'libs/less-1.1.3.min.js',
        'script.js'
    ),

This bundle assumes that the Java binary is available and already in your PATH.  This is almost always the case.  If --for whatever reason-- you need to specify the absolute path to the binary, you can do so in the java_binary_path_overrides array().  You can add as many paths as you'd like and may mix and match paths for different operating systems.  Invalid paths will simply be ignored.

### 3. Triggering Minification

Add the minify command to a before filter.  This is something that should run before every page load.  Make sure that this only runs in the development environment and not in production.

    Route::filter('before', function()
    {
        // minify JavaScript

        if($_SERVER['LARAVEL_ENV'] == 'development')
        {
            Bundle::start('closure-compiler');
            Closure_compiler::minify_js();
        }
    });

**Note:** Your LARAVEL_ENV variable may be configured differently.  Please see the [http://laravel.com/docs/install#environments](Laravel Documentation on Environment) for more information.

### 4. Using the Minified Script

Simply make a call to the minified .js file much like you would for any .js file.   No need to call any of the JavaScript files that you have minified.  They're all included in the output file.  It's very reasonable to minify all of your scripts and to only load script.min.js in your layout view.

### License

The closure-compiler bundle is released under the MIT license.

The Google Closure Compiler has been included in this package (in the vendor directory).  It is important to note that the Closure Compiler has been released under the Apache License, Version 2.0.

More information can be found here: [http://www.apache.org/licenses/LICENSE-2.0](http://www.apache.org/licenses/LICENSE-2.0)