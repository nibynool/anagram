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
- **Symfony Stopwatch** *DEV & Test environments only* - To enable profiling
- **justinrainbow json-schema** *DEV & Test environments only* - JSON Schema validation.


### Installation

##### Installation from source
- Download the source code from this repository;
- Run `composer install`;
- Configure your web server to point to the `web` subdirectory.

##### Installation from package
- Download the zip file associated with the release on GitHub;
- Unzip the package into your webserver's root directory.

##### Configuration
- For speed, this app will automatically use PSpell if it is available.  To test without
PSpell either code modifications will need to be made to the `WordFinderValidator` class
or the PSpell extension can be removed from PHP.
- The default configuration is to use the `Simple` possible match finder as it is the
most complete.  To change this to the `HeapsAlgorithm` the value of `wordfinder_generator`
can be set in `parameters.yml`. 

### Limitations

- If Heaps Algorithm is used to generate possibilities, substrings are not generated;
- Performance (both speed and RAM utilisation) for this script degrades exponentially
as more letters are provided in the input string; realistically no more than 8 letters can
be provided; error handling has not been implemented for this situation.

### Profiling Data

##### Potential Anagram Algorithms
- **Heaps Algorithm:** 8 characters will use 2Mb of RAM and run for approximately 0.5 seconds.
- **Simple:** 8 characters will use 11Mb of RAM and run for approximately 0.5 seconds.

##### Valid Word Detection (with Simple Potential Anagam Algorithm)
- **PSpell extension:** 8 characters will use 11Mb of RAM and run for approximately 0.1
seconds.
- **Dictionary file scanning:** 8 characters will use in excess of 22Mb of RAM and run for
more than 30 seconds.

##### Viewing Profiling Data
This application is developed utilising Symfony.  The provided code has been configured
to run in Symfony's development mode.  This enables the debug toolbar in HTML views and
also records profiling data in the Symfony profiler.  To access the profiler navigate
to `/_profiler`, click on the token for the request you want to view, select
`Performance` in the left menu.

Key items to view in the profiler are:
- `service.wordfinderValidator.validateInput` for input validation
- `service.anagramGenerator.generatePossible` for possible combination generation
- `service.anagramGenerator.filterPossibilities` for dictionary matching

### End-point Definitions
- **`/`** - The root end-point has been left as the default Symfony page to verify valid
installation.
- **`/ping`** - The ping end-point is required to return a 200 OK response.  This has been
included in the HTTP headers and a JSON payload defining the code and a success message
is returned in the body.
- **`/wordfiner/<input>`** - The word finder end-point is required to return a JSON array
of words that can be constructed from the string provided as `<input>` in the URL.  To
display the data generated through this code demonstration a JSON object containing
elements for the input value, invalid combinations and valid combinations is returned.

### Potential Improvements
Given more time and assuming deployment to a production environment the following options
should be considered (these options may not achieve any performance benefit, but should be
assessed):
- Implement a split-half search with caching for when PSpell is not available;
- Create a custom dictionary in a key-value store with the key being the letter combination
in alphabetical order and the value being an array of all possible words;
- Improve the Heaps Algorithm to also generate sub-string anagrams;
- Optimise the anagram generation to utilise less memory during generation;
- Investigate use of ElasticSearch or similar technologies to improve overall performance.

### Time Invested
This project was completed in the equivalent of 1 business day. The following tasks are
included in this time:
- Initial research;
- Solution design;
- Code development;
- Test development (and refreshing knowledge of PHPUnit);
- Documentation;
- Profiling; and
- Rubber ducking as part of solution validation.