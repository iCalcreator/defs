#defs

*defs* is a PHP definition handle class package,

managing keyed data like

- configurations
- definitions
- locales
- etc

on a class / namespace / HTML page / mvc / component / module / plugin / system (aka node) basis.

The purpose of *defs* is five-folded:

* providing keyed data

    > 'on-demand',

* using the 'separation-of-concern' paradigm,

   > managing configuration/definition/locale sources, not mixed up in business logic,

* contributing higher security

    > placing classified or sensitive key data outside the webserver document root,

* consolidating, simultaneously, multiple structured data sources

    > csv / ini / php / text / PO source files,
    >> using PHP scripts as defs data source, provides adaptability to any PHP software,

    > database source, currently, MySQL/MariaDB,

    > access to the lastest updated source data, using ttl logic, on each date source base.

* supporting

    > highly demanding and/or changing environments.

*defs* operates in a singleton mode, on node basis (as defined above), or as single object instances.

*defs* utilizes (opt) any PEAR Log complient log class, supporting 'log' and 'flush' methods.

The *defs* class package includes

- the PHP *defs* factory, base and utility classes, doing the hard work,
- a comprising test set, demonstrating *defs* use and capabilities.
