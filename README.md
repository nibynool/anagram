# Anagram Finder
A sample Symfony web app to accept a string of characters and find valid anagrams of the
string.

### Technology Justification
Symfony 3.4 was chosen as the framework for this project as the primary developer has
previous experience with Symfony versions up to 2.7 and wanted to extend his knowledge
whilst fulfilling the project requirements.

##### Additional Packages
The following additional packages were used to reduce development overhead and add
functionality beyond the base specifications without additional work:
- **FOS REST Bundle** - To enable content-type detection;
- **JMS Serializer Bundle** - To support JSON and XML encoding in conjunction with FOS
REST Bundle;
- **BazingaRestExtraBundle** *DEV & Test environments only* - To simplify testing REST
API responses;
- **justinrainbow json-schema** - *DEV & Test environments only* - JSON Schema validation.
### Installation

##### Installation from source
- Download the source code from this repository;
- Run `composer install`;
- Configure your web server to point to the directory the source is installed in.

##### Installation from package
- Download the zip file associated with the release on GitHub;
- Unzip the package into your webserver's root directory.