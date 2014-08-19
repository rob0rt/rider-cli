Rider
---------
A Hack Application framework


## What is Rider?
Rider is an application framework for Hacklang, Facebook's PHP library designed
for HHVM. Rider is designed to take care of most of the infrastructre, so you
just have to worry about building your application.`

### Rider CLI
Rider is used with the rider CLI, which includes a quick and easy way to create
controllers, a simple initalizer which downloads Rider-lib, Rider's helper
library, sets up the entry point, and contains the build too. Rider uses a
static Class map for autloading, and a statically generated URI map, which maps
request paths to controllers.


## Usage
Once the Rider helper CLI is installed, simply create a directory, and run
`rider init`. This sets up the directory to be a rider project, and adds a new
nginx rule to map the new endpoint to the entrypoint file.

### Creating a new controller
Controllers can be created manually, but the preferred route is by running
`rider controller`. This will walk you through the creation of the controller,
and add in the new, empty class in the `controllers` directory, ready for you
to implement whatever you choose.

### Building
Whenever you change a class or a path, you have to re-create the URIMap and the
AutoloadMap. To do this, simply run `rider build` from the root of your
project.
