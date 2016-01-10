Simple memorizer 2 is a lightweight web application that helps to efficiently memorize any question-answer sets. This is the second version of the app which was previously developed using (currently obsolete) Codeigniter 2.1 framework. This version was internally redesigned and rewritten using Laravel 4.2.

This is my after hours side project, which I use for testing of various programming solutions and techniques. It also provides sample code of mine.

## How it works

- user have to create an account and log in to use the app
- any user can create his own set of questions and answers to be memorized
- while in learning mode system presents random questions to the user
- user clicks "I know" button if he knows the answer, "I don't know" if not, or "Show answer" to show the answer
- there is no need to provide the answer in any way, user does not have to prove he knows it
- the more times user will mark particular questions as well known, the less they will be asked
- analogously, questions with more wrong answers will be asked more often
- eventually answers to all questions should be known by user

## Features

- questions and answers management via [jTable](http://www.jtable.org) based web interface
- learning answers to questions using built in learning module built with [Twitter Bootstrap](http://getbootstrap.com)
- REST API allowing 3rd party applications to use the system features, or even create independent client applications
- CSV import and export of questions, answers and number of good/bad answers

## Learning mode

In learning mode app serves user subsequent random questions. The probability of appearance of the particular question depends on previous user answers.

Two counters are stored in the database - number of good answers, and number of bad answers. Every time user declares that he knows the answer, number of good answers is increased. Analogously number of bad answers is increased when user declares that he does not know the answer to the particular question.

Ratio between known, and not known answers decides about probability of question to won. Ratio is recalculated every time one of counters increases (so every time user declares he knows or does not know the answer to particular question) and stored (for a caching purpose) in database. Then database stored ratio is used for next question randomization.

## Code design details

Main application layers are: web interface, REST API, and data repositories.

### Web interface

The entire web application is built on the top of REST API, so every action possible to do via web interface is also possible to do via API. All web controllers uses ApiDispatcher class, which internally call API methods (without making an actual web call). Web controllers are tested against mocked ApiDispatcher, so correctness of interaction with API is checked. No data is stored or retrieved from database while testing web interface.

### REST API

Controllers responsible for API methods uses injected repositories to access app data. Eloquent models are not used directly in any controllers. Repositories are injected into API controllers with Laravel IoC automatic resolution. Unit tests of API controllers tests interaction with repositories, and correct format of response. Again, not data is stored or retrieved from database while testing API methods.

### Repositories

Repositories encapsulates common operations on the data. Eloquent ORM models are used within repositories to provide data persistence. Repositories are tested against storing and retrieving real data from database. This is the only application layer that uses database while testing.

## System requirements

- LAMP stack (webserver + PHP + MySQL)
- PHP >= 5.5
- MySQL >= 5.5

## Setup

- clone repository
- create MySQL database and store it's credentials in config file
- run composer install command
- run artisan database migration command
- run artisan database seed command to seed database with sample data
- domain (if used) should point to 'public' folder
