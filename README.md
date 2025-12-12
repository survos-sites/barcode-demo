# barcode-demo

Still on 7.4 because of symfony/panther



Demonstrates survos/barcode-bundle, also uses survos/bootstrap-bundle and survos/scraper-bundle

See the demo https://barcode-demo.survos.com/

#### Notes about this repos

### Run the demo locally

```bash
git clone git@github.com:survos-sites/barcode-demo.git && cd barcode-demo
composer install
bin/console doctrine:schema:update --force
bin/console app:load -n
symfony server:start -d
symfony open:local --path=/
```

### Testing

```bash
APP_ENV=test bin/console doctrine:schema:update --force && bin/console app:load
vendor/bin/phpunit tests/Crawl
```


Now test.  It uses the same database for dev and test, but for completeness you can reload
```bash
bin/console doctrine:schema:update --force --env=test
bin/console app:load-products --env=test
vendor/bin/phpunit --testdox-text=testdox.txt
cat testdox.txt
symfony open:local --path="/info"
```


symfony open:local --path="/internal/health"
read -p "press any key to delete the database" -n 1 -s
rm products.db
symfony open:local
symfony open:local --path="/internal/health"

```

## Run Locally (local php, Symfony CLI)

Home page will show barcoded images and the image libraries that are installed locally (gd and/or imagick).
Add 127.0.0.1 barcode.local in your host file
```bash
docker compose up -d
#symfony composer install
#symfony run yarn install
#symfony run yarn dev

#symfony console doctrine:migrations:migrate -n
#symfony console app:load-products
# if using symfony proxy
#symfony proxy:domain:attach barcode-demo
#symfony server:start -d
# open https://barcode-demo.wip or https://localhost:8000 (or whatever the next port is)
```
Check http://barcode.local:8201/ in browser
## Install with Docker, including PHP

```bash
docker-compose up
```

Then go to http://localhost:8201/  Note that gd is not installed.




Base on https://medium.com/@dotcom.software/serving-resized-s3-images-on-the-fly-6b052ee3b0ca

docker-compose exec php sh

Demo for barcode-bundle [survos/barcode-bundle](https://github.com/survos/BarcodeBundle), a Symfony bundle that exposes the methods of picqer/php-barcode-generator.

## Run This Demo Locally

### Requirements

* composer
* PHP 8
* Symfony Server (for local testing)

###

To auto-update this readme and other docs, see
https://www.linkedin.com/pulse/how-easily-include-file-content-your-github-readmemd-stijn-dejongh/
See https://askubuntu.com/questions/992448/how-to-execute-a-bash-script-from-github to run all this...

### Setup

These are the steps to recreate this demo locally.

```bash
DIR=barcode-demo && mkdir $DIR && cd $DIR && symfony new --version=next --webapp .
composer config extra.symfony.allow-contrib true
symfony server:start -d
composer req survos/maker-bundle --dev
composer req survos/barcode-bundle symfony/asset-mapper survos/bootstrap-bundle
bin/console make:controller AppController
sed -i "s|Route('/app'|Route('/'|" src/Controller/AppController.php
sed -i "s|'app_app'|'app_homepage'|" src/Controller/AppController.php
symfony open:local

# replace the default base with our bootstrap/bs5 theme
cat > templates/base.html.twig <<END
{% extends "@SurvosBootstrap/%s/base.html.twig"|format(theme_option('theme')) %}

{% block javascripts %}
    {{ importmap('app') }}
{% endblock %}
END

# add bootstrap csss
echo "import 'bootstrap/dist/css/bootstrap.min.css'" >> assets/app.js
echo "import 'bootstrap'" >> assets/app.js

bin/console survos:make:menu App
bin/console survos:make:command app:import-countries "Import the countries into a database" -q

echo "name,string,55,no," | sed "s/,/\n/g"  | bin/console make:entity Country
echo "alpha2,string,2,no," | sed "s/,/\n/g"  | bin/console make:entity Country
echo "alpha3,string,3,no," | sed "s/,/\n/g"  | bin/console make:entity Country

// this no longer works, key elements removed in BetterReflection
cat << 'EOF' | symfony console survos:class:update  App\\Command\\AppImportCountriesCommand --diff \
  -m __invoke \
  --use App\\Entity\\Country \
  --use "Symfony\Component\Intl\Countries" \
  --inject "App\Repository\CountryRepository" \
  --inject 'Doctrine\ORM\EntityManagerInterface $em' \

  && vendor/bin/ecs check src/Command --fix
        $em->createQuery('DELETE FROM ' . Country::class)->execute();
        // the invoke body goes here, NOT the entire signature
        $countries = Countries::getNames();
        foreach ($countries as $alpha2 => $name) {
            $country = new Country();
            $country
                ->setName($name)
                ->setAlpha2($alpha2);
            $em->persist($country);
        }
        $em->flush();
        $io->success(sprintf("%d countries imported.", $countryRepository->count([])));
EOF


# create the entity from symfony/maker-bundle
git clean -f src
cat <<'EOF' | sed "s/:/\n/g"  |  bin/console make:entity Country
name:string:55:no
alpha2:string:2:no
alpha3:string:3:yes
EOF
cat src/Entity/XX.php

# commands that come from a maker namespace don't have access to STDIN (for some reason), so create the class first.
symfony composer req symfony/intl
symfony console survos:make:command app:import-countries --description "Import the countries from symfony/intl to the database"

echo "x" | symfony console survos:class:update  App\\Command\\AppImportCountriesCommand -m __invoke \n
  --use Doctrine\EntityManagerInterface
  --inject EntityManagerInterface$em


cat <<'EOF' | symfony console make:method  App\\Command\\AppImportCountriesCommand -m __invoke --inject Doctrine\EntityManagerInterface$em
// the invoke body goes here, NOT the entire signature
        $countries = \Symfony\Intl\Countries::getNames();
        foreach ($countries as $alpha2=>$name) {
            $country = new Country();
            $country
                ->setName($name)
                ->setAlpha2($alpha2);
            $this-em->persist($country);
        }
        $this-em->flush();
EOF
git diff src/Command


```

We need an entity class, we're going to use Country, and populate the table using Symfony's intl component.  We'll need a database, if you're running locally and have sqlite installed, use that.  Or any database that Doctrine supports.  Later we'll move this to postgres for heroku.  By default, Symfony assumes you're using a mysql database, so change it in .env.local


    # make the entity / repo


git clean src/Service/ -f
bin/console make:service X
// generates XService in src/Service

// in SurvosMakerBundle, using a Maker (for prompting)

tmpfile=$(mktemp /tmp/snippet)

snippet_file=$(mktemp)

cat <<'EOF' > $snippet_file
// src/Service/XService.php
public function test(): void {}
EOF

cat <<'EOF' >
// src/Service/XService.php
public function test(): void {}
EOF
bin/console x:make:method --body $


cat > xx <<END_OF_HEREDOC_MARKER
defscrollback ${MAX_LINES}
END_OF_HEREDOC_MARKER


cat $snippet_file

: ...
rm "$tmpfile"

cat <<'EOF' >
// src/Service/XService.php
public function test(): void {}
EOF
cat <<'EOF' | symfony console make:method  AppService -m calcScore
// src/Service/XService.php
public function test(): void {}
EOF



echo "x" | bin/console make:method




    bin/console make:entity Country
       # name, string, 55, no (not nullable)
       # alpha2, string, 2, no (not nullable)

    bin/console doctrine:schema:update --force

    composer require make orm-fixtures --dev
    composer require symfony/intl

    bin/console make:fixtures CountryFixtures

Loading the database is trivial,

```
use Symfony\Component\Intl\Countries;
use App\Entity\Country;

class CountryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $countries = Countries::getNames();
        foreach ($countries as $alpha2=>$name) {
            $country = new Country();
            $country
                ->setName($name)
                ->setAlpha2($alpha2);
            $manager->persist($country);
        }
        $manager->flush();
    }
```

Load the countries

```bash
symfony console doctrine:fixtures:load -n
```

It relies on bootstrap and jquery, loaded via Webpack Encore.  Although this is more setup than simply loading those libraries from a CDN, it is also a best practice and more representative of a real-world application.

```bash
composer req symfony/webpack-encore-bundle && yarn install
```
`
Get bootstrap and jquery

```bash
yarn add bootstrap jquery select2@4.0.6 @popperjs/core
```

and add them to app.js and app.css to make them global.  The select2 configuration is all done in PHP (via data-* attributes) so we will simply initialize the appropriate elements here.

```javascript
// app.js
require('jquery');
require('bootstrap');
require('../../vendor/tetranz/select2entity-bundle/Resources/public/js/select2entity.js');

// initialize the select2 elements.
$('.js-select')
````

```css
/* assets/app.css */
@import "~select2/dist/css/select2.min.css";
@import "~bootstrap/dist/css/bootstrap.min.css";
```

Compile the assets.  First allow a global jQuery object

```js

    // uncomment if you're having problems with a jQuery plugin
    .autoProvidejQuery()

```

```bash
yarn run encore dev
```

## Bootstrap Bundle

This helper bundle gives us a basic landing page, a base that loads the assets, and some menus.

    composer require survos/bootstrap-bundle

Replace base.html.twig so that it extends the base from the landing bundle.  This will load the css and javascript from the compiled webpack.

echo '{% extends "@SurvosBootstrap/sneat/base.html.twig" %}' >templates/base.html.twig


## Finally, start using select2EntitiesBundle

Now we've got a basic website with an entity, and we want to create some pages and forms.

    composer req tetranz/select2entity-bundle

Update twig.yaml to include rendering select2

```yaml
    form_themes:
        - 'bootstrap_4_horizontal_layout.html.twig'
        - '@TetranzSelect2Entity/Form/fields.html.twig'
```

### Create a Form and Add the country field

```bash

    bin/console make:form SingleSelectForm

```
Open up your form and configure the field, e.g.

```php
            ->add('single_country', Select2EntityType::class, [
                'multiple' => false,
                'remote_route' => 'app_country_autocomplete',
                'class' => Country::class,
                'primary_key' => 'id',
                'text_property' => 'name',
                'minimum_input_length' => 1,
                'cache' => 0,
                'page_limit' => 10,
                'required' => false,
                'allow_clear' => true,
                'language' => 'en',
                'placeholder' => 'Select A Single Country',
                'attr' => [
                    'class' => 'js-select2'
                ]
            ])
```


The 'class' in 'attr' is very important, since we use that in app.js to initialize.
```

### Create and Configure the Controller

```bash
    bin/console make:controller AppController
```


The autocomplete ajax call is a simple query, using the repository created in make:entity.

```php

// add to AppController.php

    /**
     * @Route("/country_autocomplete.json", name="app_country_autocomplete")
     */
    public function CountryAutocomplete(Request $request, CountryRepository $repository)
    {
        $q = $request->get('q');
        $matches = $repository->createQueryBuilder('c')
            ->where("c.name LIKE :searchString")
            ->setParameter('searchString', $q . '%')
            ->getQuery()
            ->getResult();

        $data = array_map(function(Country $country) use ($request) {
            return ['id' => $country->getId(), 'text' => $country->getName()];
        }, $matches);
        $data = array_values($data);

        $data = ['results' => $data];
        return new JsonResponse($data);
    }
```

Of course, you need a route to land on, then you'll instanciate the form and send it to be rendered in a twig template.

```php
// add to AppController.php
    /**
     * @Route("/", name="home")
     */
    public function showForm(Request $request)
    {
        $defaults = [];
        $form = $this->createForm(\App\Form\SingleSelectFormType, $defaults);

        return $this->render('app/showForm.html.twig', [
            'form' => $form->createView()
        ]);
    }
```

## Compile the assets and run

```bash
yarn  dev
symfony serve
```

Open the web page, and you should now have a select2 form.



## Heroku

Initialize heroku and add a database

    heroku create <name>
    heroku addons:create heroku-postgresql:hobby-dev

Add node to buildpack

    heroku buildpacks:add heroku/nodejs
    git push heroku main

Add Sentry to make your life easier!







