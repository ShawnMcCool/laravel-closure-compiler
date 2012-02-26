# Google Closure Compiler Bundle, by Shawn McCool

A Laravel [Closure Compiler](https://developers.google.com/closure/compiler/) bundle, installable via the Artisan CLI:

    php artisan bundle:install closure-compiler

**Important:** Closure Compiler requires that Java is installed.  Consequently, it can run on virtually any operating system.

### Bundle Registration

Add 'closure-compiler' to your **application/bundles.php** file:

    return array(
        'closure-compiler'
    );

### Configuration

In the bundle's config file simply verify that your script path (the location in which your javascript files reside) and that your script_output_file is defined. The script_output_file will contain the resulting minified JavaScript.

Then, add the JavaScript files that you'd like to be minified to the 'minify_scripts' array.  An example:

    'minify_scripts' => array(
        // included some examples, replace these with your own
        'libs/less-1.1.3.min.js',
        'script.js'
    ),

This bundle assumes that the Java binary is available and already in your PATH.  This is almost always the case.  If --for whatever reason-- you need to specify the absolute path to the binary, you can do so in the java_binary_path_overrides array().  You can add as many paths as you'd like and may mix and match paths for different operating systems.  Invalid paths will simply be ignored.

### Triggering minification

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

### Using the Minified Script

Simply make a call to the minified .js file much like you would for any .js file.   No need to call any of the JavaScript files that you have minified.  They're all included in the output file.  It's very reasonable to minified all of your scripts and to only load script.min.js in your view.

### License

The Google Closure Compiler is released under the Apache License, Version 2.0.

More information can be found here: [http://www.apache.org/licenses/LICENSE-2.0](http://www.apache.org/licenses/LICENSE-2.0)