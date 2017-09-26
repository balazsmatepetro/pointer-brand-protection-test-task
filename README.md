#Search engine aggregator assignment

This branch contains the completed assignment. The provided code can be located on branch: original-source

Write a simple search(engine) result aggregator library. It will be a standalone and frameworkindependent
library so that we can use as a third party library in any framework we like. You don’t
have to create an application (controllers/views) to actually use the library and you don’t have to
publish the library on Github/Bitbucket. The groundwork for the library is already done for you,
you only have to finish it.

#Assignment
- Implement ResultInterface and ResultsInterface.
- Implement at least one search engine (Google/Yahoo/Bing/Yandex).
- Implement the EngineAggregator class using the implementations from the previous steps.
- Create a PHP-file where you will actually use the EngineAggregator to search on a keyword
and output the results.

#Engine(s)
How you implement the search engine is totally up to you, as long as it has a unique engine
identifier (to mark a result as the result of that specific search engine) and returns an implementation
of a ResultsInterface. For this assignment only fetching the results of only the first page of the search
engine results will be sufficient.

#Result(s)
The search engine aggregator has to combine the results from the first page from every search engine.
Duplicates are not allowed. In case of a duplicate, you should only add the source (engine identifier)
to the existing search result with the following result. A search result does not need to contain more
than what’s defined in the ResultInterface.

#Engine aggregator
The engine aggregator should use one or many Engine’s to search for a keyword/query and merge
the results together (as described above). The EngineAggregator should, just like the Engine, return a
ResultsInterface. The only difference is that the result will be a merge the results from the aggregated
engines.

#Guidelines
- Use PHPDoc blocks in your code.
- Use PSR-standards.
- Try to keep the SOLID principles in mind as much as possible.
- Feel free to add unit-tests (not mandatory)

Estimated time for the assignment: 60 minutes.
