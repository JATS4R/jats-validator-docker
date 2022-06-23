## Docker

The Dockerfile uses the [Schematron skeleton](https://github.com/Schematron/stf/tree/master/iso-schematron-xslt2) to build an XSLT 2.0 file from an input Schematron file. 

The Docker container runs an Apache web server listening on port 80, hosting a set of PHP endpoints that validate an input XML file against the appropriate JATS DTD, format the XML, and/or validate the XML against the Schematron rules using `SaxonProcessor`.

## Usage

1. Build the Docker image: `docker build . --platform linux/amd64 --tag jats-validator`
1. Start the Docker container: `docker run --rm --publish 4000:80 --name jats-validator jats-validator`
1. Open <http://localhost:4000/> and choose a JATS XML file to validate.
