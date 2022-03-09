<p align="center"><a href="https://kindhumans.com" target="_blank"><img src="https://kindhumans.com/wp-content/themes/kindhumans-theme/dist/development/images/kindhumans_vertical_logo.svg" width="200"></a></p>


## About Kinja App

Web Application to manage Kindhumans store features like:

- Memberships
- Dropships
- Subscriptions

## Requirements/Dependences

- PHP 8
- Composer
- Laravel 9
- Redis
- Mailhog
- Meilisearch
- Supervisor
- PostgresSQL

## How to install?

We have two choices to install this project. One is install the requirements/dependences mentioned above in your local machine and run it. Or install it using `Laravel Sail`. We recommend to use this last option. It will to avoid issues related to dependecies or requirements of the project.

First, is necessary to have composer installed in your local machine. Please follow the documentation of composer to get it installed in your local machine in case you
don't have it installed yet. https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos

Once you have ready composer, please go to the project root and run `composer install`. It will install the project dependences.

## Pre-requisites
Please copy the `.env.example` file to load our env values. Please run:

```bash
cp .env.example .env
```

### Application Urls.
This app is a client of a kindhumans store which is necessary define some env connection values with our store installed locally. We have a few env variables
to define some values. Look:

```
APP_URL=http://localhost:8000
FRONTEND_URL=http://localhost:8000
ASSET_DOMAIN=https://kindhumans.com # Should no change, but if you want to redirect the asset urls base domain to another domain, change it.
CLIENT_DOMAIN=https://your-local-host-here # Replace with the local store domain.
```

The `ASSET_DOMAIN` var will be used as the base url for images. We don't store images on this app, normally we have a link of each images to the store connected. By default we
can use kindhumans.com, but if you want to load the images of your local kindhumans store, change it for the url used in your local machine.

The `CLIENT_DOMAIN` is the url of your store in your local machine. 

### Database.
We are using PostgresSQL as our database engine, it means that we need to define this env vars in our .env file. If you're using `Laravel Sail`, you should these vars with the default values, otherwise, you should set the right values to connect to postgres in your local machine.

```
DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
DB_DATABASE=omnipress
DB_USERNAME=postgres
DB_PASSWORD=postgres
```

### Mailer

By default the project has mailhog installed as the email client. If you use `Laravel Sail` leave these env vars as it comes in the default .env.example. Otherwise, you should change those var values to the right values of your email client.

```
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS=noreply@kindhumans.com
MAIL_FROM_NAME="${APP_NAME}"
MAIL_ENCRYPTION=null
```

### WooCommerce API Keys.
To connect with WooCommerce we only need to be sure of the port we're using on our local machine. If you're using localwp to run your WordPress in your laptop, probably the port will be 10003 or 10009. To be sure of this, please run in your terminal:

```bash
$ sudo lsof -i -n -P | grep TCP
```

It will show you the port used. So, look for the ports used by `nginx`. Probably you will see something like:

```bash
nginx       787        dgaitan    7u  IPv4 0x8be2049a08e8bf2b      0t0    TCP *:10003 (LISTEN)
nginx       788        dgaitan    7u  IPv4 0x8be2049a08e87f2b      0t0    TCP *:80 (LISTEN)
nginx       788        dgaitan    8u  IPv6 0x8be20495371cbb83      0t0    TCP *:80 (LISTEN)
nginx       788        dgaitan    9u  IPv4 0x8be2049a08e8944b      0t0    TCP *:443 (LISTEN)
nginx       789        dgaitan    7u  IPv4 0x8be2049a08e87f2b      0t0    TCP *:80 (LISTEN)
nginx       789        dgaitan    8u  IPv6 0x8be20495371cbb83      0t0    TCP *:80 (LISTEN)
nginx       789        dgaitan    9u  IPv4 0x8be2049a08e8944b      0t0    TCP *:443 (LISTEN)
```

So, the localwp normally use a port greater than 10000, so if you see a port between 10000 or 100010, probably will be that.

Once you have the port of your localwp, you can set the value in your `.env` to connecto to your store.

```
WOO_CUSTOMER_KEY=ck_5f944eb60ada2cfb7ffafb923603f28b99f2dd80
WOO_CUSTOMER_SECRET=cs_c20d091b9a06ed2e0e4530f3e1d0fd5ef97ea049
WOO_CUSTOMER_DOMAIN=http://host.docker.internal:10003 # replace the port with the port in your local.
WOO_TIMEOUT=50
```

### Google Auth Login
To login into the app we have to choices: login using google oauth, or just activate the register.

To login into the app using google oauth is necessary generates a google auth key. To do this, please go to the Google Developer Console (https://console.cloud.google.com/apis/dashboard) and create a new api keys.

Steps to create a google api key:

1. Go to https://console.cloud.google.com/apis/dashboard
2. Click on **Credentials** in the left sidebar. (Is the 3rd option)
3. Click on **Create Credentials** then click on **OAuth Client ID**
4. On Application type select **Web Application**
5. Set the App Name
6. Add a new Authorized Javascript origins. Add http://localhost:8000 and http://127.0.0.1:8000
7. Add Authorized redirect URIs and define the next url: `http://localhost:8000/oauth/google/callback`
8. Save it and it will take a few minutes to make effects.

So far, you can add the API Keys generated by Google in the top right inside the form where we defined the step above. Copy the keys and add it to the .env file.
You'll see the variables like this:

```
ACTIVATE_TRADITIONAL_REGISTRATION=false
GOOGLE_CLIENT_ID=578350767445-pbhibf17vh47j2oa7cslcmpl8abfsbmm.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=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
GOOGLE_REDIRECT=http://localhost:8000/oauth/google/callback
```

You can try using the current api keys or set the new one.

If you want to register without google, you'll to set the env var `ACTIVATE_TRADITIONAL_REGISTRATION` to `true`. It will activate a new route to register into the app.

1. Go to `http://localhost:8000/register`
2. Fill the form using an email ending with @kindhumans.com
3. You'll need to confirm your email.
4. Go to `http://localhost:8025` and see the new email confirmation.
5. Click on the confirmation link.
6. That's all.

### Stripe
We use stripe to process payments. So, let's define the api keys:

```
STRIPE_KEY=your-stripe-key
STRIPE_SECRET=your-stripe-secret
CASHIER_CURRENCY=usd
```

Please contact to one of the kindhumans dev team member to get the stripe keys.

## Install with Laravel Sail (Docker).

See: https://laravel.com/docs/9.x/sail

`Laravel Sail` is a package to install a project using Docker. Having said this, the first requirement here is Docker. Once you have installed Docker in your local machine add an alias
to your bash to use `sail` easily. So, please add to your bash:

```bash
alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
```

Then load this changes in your termain sourcing it!

```bash
source ~/.zshrc
```

Replace `.zshrc` with the bash profile you're using.

So, now we should be able to use `sail` command in our terminal. Before of install the docker images, let's be sure we are fine with our environment variables.

### Environment

Let's run our first sail command to build our docker image. Please run the following command:

```
$ sail build --no-cache
```

The last command will be run only by the first time. Once the contianer has been installed, let's run:

```
$ sail up
```

It will run the docker image and we should be able to see the site running on http://localhost:8000 with an error. Of course we need to migrate our database.

### Running database migrations:

Every time you need to run new migrations on our database you'll need to run the next command:

``` bash
$ sail artisan migrate
```

Then run:

```bash
$ sail artisan kinja:sync-permissions
```

And then goes to the browser to http://locaslhost:8000 and login with your @kindhumans.com email or register into the app going to `http://localhost:8000/regiser` (be sure of have `ACTIVATE_TRADITIONAL_REGISTRATION` en var to `true`). You should be able to login to the admin and then you'll need to give super admin permissions
to yourself. So, to do this please go back to  your terminal and run:

```bash
$ sail artisan kinja:become-super-admin youremail@kindhumans.com
```

Of course, replace `youremail@kindhumans.com` with the email you used to login in the admin.

## Adding Kindhumans Data

Of course we need data in our admin to keep it synced with our kindhumans store. So, let's go to our admin with super admin perms and click on `Api Tokens` (http://localhost:8000/user/api-tokens) and let's create a new one with all the perms. Then copy the API key generated and let's go to the kindhuman store wp dashboard and click on `Tools > Kinja` and set the url of our admin and the api key just 
generated.

To import all the data from out store locally we have two options. Do it via API and via imports. Both works, the difference is in speed. We recommend import the data importing it via csv. So, to do it we should go to the project root of our kindhumans store and run the next wp cli command:

```bash
$ wp kinja export_customers
$ wp kinja export_products
$ wp kinja export_orders
$ wp kinja export_memberships
```

The we should copy those csv files and save it on a `csv/` folder in the root of this project. Then we should import the customers first. 

**Note:** The order of these imports are important to avoid errors of dependency data.

1. First import customers:
```bash
$ sail artisan kinja:import customers csv/Customers_Kindhumans.csv
```

2. Import Products
```bash
$ sail artisan kinja:import products csv/Products_Kindhumans.csv
```

3. Import Orders
```bash
$ sail artisan kinja:import orders csv/Orders_Kindhumans.csv
```

4. Finally import memberships
```bash
$ sail artisan kinja:import memberships csv/Memberships_Kindhumans.csv
```

## Tasks after finished.

Link storage.

```bash
$ sail artisan storage:link
```

If you want to run queue jobs, run:

```bash
$ sail artisan queue:work
```

**Note** Be sure of have running up `sail up` always, otherwise these commands will not work.
