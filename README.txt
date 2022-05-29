Usage example

    $ curl -X POST https://localhost:8002/job/add-new --data "hello"
    {"message":"Job started","jobId":"f0756fbf-50cf-41d6-a1ee-88c5652d8d50"}%
    $ curl https://localhost:8002/job/status/f0756fbf-50cf-41d6-a1ee-88c5652d8d50
    {"status":"started"}%

On another termianl:

    $ symfony console messenger:consume async -vv

This will take 2 minutes to process the job. It this is to slow for you add the
following line in .env.local:

    JOB_DURATION=0

The JOB_DURATION value is expressed in seconds.

Back the original terminal:

    $ curl https://localhost:8002/job/status/f0756fbf-50cf-41d6-a1ee-88c5652d8d50
    {"status":"completed","result":"HELLO"}%

Run all tests:

    symfony run bin/phpunit

Run only unit tests:

    symfony run bin/phpunit --exclude-group integration

To get the code coverage report:

    XDEBUG_MODE=coverage symfony run bin/phpunit --coverage-html reports/

-EOF