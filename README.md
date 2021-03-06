[![Build Status](https://travis-ci.org/PeekAndPoke/metacore.svg?branch=master)](https://www.travis-ci.org/PeekAndPoke/metacore)
[![Coverage Status](https://coveralls.io/repos/github/PeekAndPoke/metacore/badge.svg?branch=master)](https://coveralls.io/github/PeekAndPoke/metacore?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/PeekAndPoke/metacore/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/PeekAndPoke/metacore/?branch=master)
[![Dependency Status](https://gemnasium.com/badges/github.com/PeekAndPoke/metacore.svg)](https://gemnasium.com/github.com/PeekAndPoke/metacore)


# What is the MetaCore?

The MetaCore is a way to represent objects exiting in your code base.

The representation can be transferred to other systems e.g. as JSON.
                                                        
It is also intended to be a layer of decoupling between:

1. the actual code the represent the DomainModel of a project and

2. tools like code generators that can automate tasks based on the DomainModel

 
# Use-Cases

- The MetaCore-model can be used for building code-generators. Examples:

  1. Generators that builds SDKs for your Rest APIs in multiple languages
  2. Generators that builds HTML forms and validation from the pure
 definition of the DomainModel 

- The MetaCore-model can be used for building dev-tools like SwaggerUI.


# TODO

MECO-1 - 0% - implement configurable type mapping, e.g. for \DateTime or LocalDate
  -> reported 2016-10-07 by PeekAndPoke 

