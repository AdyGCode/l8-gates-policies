# Setting up and Creating Base Application

Open a terminal window and then make sure you are in the folder in which you wish to create the
application.

Run:

```bash
curl -s "https://laravel.build/l8-gates-policies" | bash
```

Once finished, you will be asked for your password to correct folder permissions as required.

Change into the project folder and bring Sail Up:

```bash
cd l8-gates-policies && ./vendor/bin/sail up
```

Leave the "sail runner" running in this window.

**Aside:** *to stop the sail runner you may use <kbd>CTRL</kbd>+<kbd>C</kbd> in the window where
it is running. Alternatively, in a second window and in the project folder, use `sail down`
instead.*

## Install Required Components

Open second terminal window and change into the project folder:

```bash
cd l8-gates-policies
```

Now we install Breeze, Laravel DebugBar, and associated files:

```bash
sail composer require laravel/breeze --dev 
sail composer require barryvdh/laravel-debugbar --dev --with-all-dependencies
sail php artisan breeze:install
sail npm install
sail npm install tailwindcss-gradients@2.x tailwindcss-elevation
sail npm install @tailwindcss/line-clamp @tailwindcss/forms
sail npm install @tailwindcss/typography @tailwindcss/aspect-ratio
```

modify the `webpack.mix.js` file's `mix.js` section:

```js
mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [

        require('postcss-import'),
        require('tailwindcss'),
        require('autoprefixer'),
    ])
    .sass('resources/sass/app.scss', 'public/css');
if (mix.inProduction()) {
    mix.version();
}
```

### Install FontAwesome:

**Fort Awesome** is the name of the company who create Font Awesome.

```bash
npm install --save @fortawesome/fontawesome-free
```

Create a new folder: `resources/sass`

Add a new file `app.scss` to the `sass` folder with the following:

```sass
// Font-Awesome
@import "~@fortawesome/fontawesome-free/scss/fontawesome";
@import "~@fortawesome/fontawesome-free/scss/regular";
@import "~@fortawesome/fontawesome-free/scss/solid";
@import "~@fortawesome/fontawesome-free/scss/brands";
```

Now run:

```bash
sail npm install
sail npm run dev
```

If asked you may need to run the `sail npm run dev` command once more.

### Adding predefined colours to Tailwind CSS

We are almost done with this step. We now want to add the full list of colours for the Tailwind
CSS (such as amber, cool gray, warm gray and more).

Edit the `tailwind.config.js` file.

Add a new line just below the first line starting with `const defaultTheme `:

```js
const colors = require('tailwindcss/colors')
```

Next modify the theme section so it reads:

```js
    theme: {
    extend: {
        fontFamily: {
            sans: ['Nunito', ...defaultTheme.fontFamily.sans],
        }
    ,
    }
,
    colors: {
        // Build your palette here
        transparent: 'transparent',
            current
    :
        'currentColor',
            white
    :
        colors.white,
            black
    :
        colors.black,
            blueGray
    :
        colors.blueGray,
            coolGray
    :
        colors.coolGray,
            gray
    :
        colors.gray,
            trueGray
    :
        colors.trueGray,
            warmGray
    :
        colors.warmGray,
            red
    :
        colors.red,
            orange
    :
        colors.orange,
            amber
    :
        colors.amber,
            yellow
    :
        colors.yellow,
            lime
    :
        colors.lime,
            green
    :
        colors.green,
            emerald
    :
        colors.emerald,
            teal
    :
        colors.teal,
            cyan
    :
        colors.cyan,
            sky
    :
        colors.sky,
            blue
    :
        colors.blue,
            indigo
    :
        colors.indigo,
            violet
    :
        colors.violet,
            purple
    :
        colors.purple,
            fuchsia
    :
        colors.fuchsia,
            pink
    :
        colors.pink,
            rose
    :
        colors.rose,

            /* Alternative names for some colours */
            primary
    :
        colors.blue,
            secondary
    :
        colors.purple,
            tertiary
    :
        colors.gray,
            danger
    :
        colors.red,
            warning
    :
        colors.amber,
            info
    :
        colors.blueGray,
            success
    :
        colors.green,
    }
,
    linearGradientColors: theme => theme('colors'),
        radialGradientColors
:
    theme => theme('colors'),
        conicGradientColors
:
    theme => theme('colors'),
}
```

Finally, modify the `plugins` section of the `tailwind.config.js` file to read:

```js
    plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
    require('@tailwindcss/line-clamp'),
    require('@tailwindcss/aspect-ratio'),

    require('tailwindcss-gradients'),
    require('tailwindcss-elevation')(['responsive']),
],
```

And then once more run:

```bash
sail npm install
sail npm run dev
```

## Publishing package files to allow customisation and configuration changes

We run the following command to publish the mail templates, pagination templates and
notifications templates. These allow us to customise the look and feel of the components. This
can be very useful.

```bash
sail php artisan vendor:publish --tag=laravel-mail --tag=laravel-pagination --tag=laravel-notifications
```

Check the `/resources/views/components` and `/resources/views/vendor` folders.

## Adding Other Helper Packages

We will add the `laravel-timezone` helper package to allow easier display of dates and times
with the local timezone, whilst storing data in the database using UTC as the reference point.

The second line is split into three parts as it is very long, but should be entered as one
single command.

```bash
sail composer require jamesmills/laravel-timezone
sail php artisan vendor:publish
           --provider="JamesMills\LaravelTimezone\LaravelTimezoneServiceProvider"
           --tag=migrations
sail php artisan vendor:publish --provider="Torann\GeoIP\GeoIPServiceProvider" --tag=config
sail php artisan vendor:publish --provider="JamesMills\LaravelTimezone\LaravelTimezoneServiceProvider" --tag=config
```

### Configuring Timezone packages

We are using two packages for the timezone detection. James Mills' Laravel Timezone, and a
required package, Torran's Geo IP Service Provider.

We need to perform a small amount of configuration to make sure that "tagging" is enabled when
caching results for the Geo Locations. Torran's Geo IP package does not allow for file or
database caching, so we either have to turn it off, or use an alternative.

Open the `/config/geoip.php` file and alter the line `cache_tags` to read:

```php
        'cache_tags' => null,
```

This will let us use the Timezone and GeoIP packages with our authentication.

### Next: [Users-Tasks](02-Users0Tasks.md)
