# Google Closure Compiler Bundle

written for use with Laravel by Shawn McCool

## Description

A Laravel [Closure Compiler](https://developers.google.com/closure/compiler/) bundle, installable via the Artisan CLI:

This bundle automatically compresses a site's JavaScript files and updates them only when necessary.  List your site's JavaScript files in a config file and when a site runs in the development environment it'll check to see if any of the JavaScript files have been updated since the last compression.  If so, it'll compress immediately and keep itself up to date.  The developer or designer would then commit their code into their repository as normal including the compressed JavaScript file.

## How to Use

### 1. Install the Bundle

    php artisan bundle:install closure-compiler

**Important:** Closure Compiler requires that Java is installed.  Consequently, it can run on virtually any operating system.

### 2. Bundle Registration

Add 'closure-compiler' to your **application/bundles.php** file:

    return array(
        'closure-compiler'
    );

### 3. Bundle Configuration

In the bundle's config file simply verify that your script path (the location in which your JavaScript files reside) and that your script_output_file is defined. The script_output_file is the file that contains the compressed JavaScript code from all files in the minify_scripts configuration array.

Then, add the JavaScript files that you'd like to be compressed to the 'minify_scripts' array.  An example:

    'minify_scripts' => array(
        // included some examples, replace these with your own
        'libs/less-1.1.3.js',
        'script.js'
    ),

This bundle assumes that the Java binary is available and already in your PATH.  This is almost always the case.  If --for whatever reason-- you need to specify the absolute path to the binary, you can do so in the java_binary_path_overrides array().  You can add as many paths as you'd like and may mix and match paths for different operating systems.  Invalid paths will simply be ignored.

### 4. Triggering Compression

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

### 5. Call the Compressed Script from your View

    <script src="{{ URL::to() }}js/script.min.js"></script>

Simply make a call to the resulting .min.js file much like you would for any .js file.  You can do this manually in your layout view as shown above, or you can use the Laravel Asset library.  Do *not* add any of the JavaScript files that are inside your minify_javascript configuration array, they're all included in the output file.  It's very reasonable to minify all of your scripts and to only load script.min.js in your layout view.

## Best-Practices

The purpose of minifying multiple files together is to reduce the number of JavaScript load calls that the browser makes.

Page load time is reduced dramatically when the necessary number of requests are reduced. This is not only because the Closure Compiler does an impressive job of reducing the size of JavaScript files.  But, also due to the networking overhead of transferring many small files.

Once the monolithic JavaScript file (which contains the minified contents of many JavaScript files) is loaded once, it's then cached by the browser.  Consequently, downloading JavaScript is unnecessary on subsequent page .  This has the end result of dramatically reducing load time for pages which use JavaScript.

It's important to note that while you might be tempted to have separate minified JavaScript files for different pages in order to only include the necessary components, that should be avoided

**Here is an example scenario to illustrate the point:**

1. Load Home Page: jquery, jquery ui, home.js (250k compressed to 60k)
2. Load Blog Page: jquery, jquery ui, blog.js (250k compressed to 60k)

One may be tempted to generate a home.min.js which contains only the files necessary for the home page and a blog.min.js which contains only the files necessary for the blog pages.  This actually has a very negative effect as you're not only ignoring browser caching functionality, but you're actually reducing performance below pre-minification levels.

Let's take a look at a few scenarios to illustrate this point:

**No Compression**

1. Load Home Page: jquery, jquery ui, home.js (250k, uncompressed)
2. Load Blog Page: jquery(cached), jquery ui(cached), blog.js (10k)

You can see here that after the initial page load the primary components (jquery, jquery ui) are already loaded and cached.  They don't need to be downloaded by the browser again.  The browser would only need to download files that it hasn't already acquired.  In this scenario that is the 10k blog.js file.

**Multiple Compressed Files**

1. Load Home Page: jquery, jquery ui, home.js (60k compressed)
2. Load Blog Page: jquery, jquery ui, blog.js (60k compressed, no cache used)

Total download: **120k**

**Single Compressed File**

1. Load Home Page: jquery, jquery ui, home.js, blog.js (70k compressed)
2. Load Blog Page: jquery, jquery ui, home.js, blog.js (everything is already cached, no download necessary)

Total download: **70k**

The optimal solution is to gather all of your common JavaScript files into a single minified file.  This file loads when someone first hits your site (or on the first page where JavaScript is used).  After this initial load there should be no further loads to JavaScript files.

If you would like to include an additional bit of JavaScript that perhaps is only used on the admin page, simply make an additional script call.

**Additional Scripts**

1. Load Home Page: jquery, jquery ui, home.js, blog.js (70k compressed)
2. Load Admin Page: jquery, jquery ui, home.js, blog.js (cached), admin.js (5k)

In this scenario the original compressed file is already cached by the browser and the user's browser would make a single additional call to download the admin.js file.  In this way your users are unaffected by the size of your admin JavaScript code.

## License

The closure-compiler bundle is released under the MIT license.

The Google Closure Compiler has been included in this package (in the vendor directory).  It is important to note that the Closure Compiler has been released under the Apache License, Version 2.0.

More information can be found here: [http://www.apache.org/licenses/LICENSE-2.0](http://www.apache.org/licenses/LICENSE-2.0)

### Troubleshooting

**Problems running on OSX**

It's possible that some OSX users will have problems running Java through the webserver in the way that this bundle does. If you're having this problem check here for a potential solution: http://stackoverflow.com/questions/7650013/java-1-6-broken-when-called-by-background-symfony-task/9791946#9791946