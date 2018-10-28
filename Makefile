.PHONY : tests lint static report coverage

all : tests lint static report

tests :
	vendor/bin/phpunit tests/ --configuration=tests/phpunit.xml

lint :
	vendor/bin/phpcs --standard=coding_standard.xml --ignore=vendor .

static :
	vendor/bin/phpstan analyze --level max src/ tests/
	vendor/bin/phpmd src/ text cleancode,codesize,design,unusedcode,naming

coverage :
	vendor/bin/phpunit tests/ --configuration=tests/phpunit.xml --coverage-text=php://stdout

report :
	vendor/bin/phploc src/