# Hierarchial taxonomy migrate

## Enabling and using the module

- Enable the module `custom hierarchial_taxonomy_migrate`.
- Import the configurations: `drush cim -y`
- Create a taxonomy vocabulary named: `custom_taxonomy`.
- Go to admin > strucuture > Migrations and you can see `Import term taxonomy` and `Set parent taxonomy` migrations. Run both migrations.
- You can see a hierachial structure of taxonomy terms created under `custom_taxonomy` as follows.

## Testing strategy

- To test the integrity of terms and its association of contents, use 
the strategy as follows: 
    - Created User roles `Parent` and `Children`, and associated some content to it.
    - Parent tagged contents are accessible to Parent roled users only. and children tagged content to Children roled users only using tac_lite(https://www.drupal.org/project/tac_lite) contrib module .

![Alt text](screenshot.png)