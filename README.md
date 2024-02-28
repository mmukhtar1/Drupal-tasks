### Login credentials

https://d10-mmukhtar-tasks.ddev.site/

username: admin, password: ******

1) Custom block plugin

Go to any article content and move to the bottom of the page (eg: https://dev-d10-mmukhtar-tasks.pantheonsite.io/node/1). You can see the stock market data fetched via an external API to the block. The `Stock Market Data` block is configured to the content bottom region.

2) Custom Entity Types

Go to content and click on the `Custom entity types` tab  or via https://dev-d10-mmukhtar-tasks.pantheonsite.io/custom-entity-types/add  and add the custom entity. 

We can add / edit / delete Custom entities from there. We can edit the existing custom entities from: https://dev-d10-mmukhtar-tasks.pantheonsite.io/admin/content/custom-entity-types

3) Custom theme registration

Install the `custom_theme_registration` theme.

Take https://dev-d10-mmukhtar-tasks.pantheonsite.io/user/register.

 the custom template used is `custom_theme_registration/templates/form/form--user-register-form.html.twig` 

if you are logged in, logout from site.
from the login page, take <b>create new account</b> tab and you will see a the registration page. 

4) Weather forcast

Weather forecast uses open weather API to call the webservice to get the forecast data of a city.

Go to Admin > Configuration > User interface > Set Forecast configuration to set the configuration (https://dev-d10-mmukhtar-tasks.pantheonsite.io/admin/config/system/weather-forecast) and configure the API key and city name.

Take Admin > Configuration > User interface > Show forecast to see the forcast of the selcted city.

5) Multi step form

Go to Admin > Configuration > User interface > Multi step form (https://dev-d10-mmukhtar-tasks.pantheonsite.io/multistep-form/demo) and navigate through each steps.

6) Migrating hierarchial taxonomy

After enabling the pre requisite modules (migrate_plus, migrate_tools, migrate_source_csv) , Go to https://d10-mmukhtar-tasks.ddev.site/admin/structure/migrate and run the migrations

The migration with source type `CSV` will migrate from a csv source. for this you have to create a vocabulary named `categories`.

7) 

Individual README files added in each modules for detailed documentation.
