.PHONY : coverage lint setup tests

all : lint tests coverage

setup :
	composer install

lint :
	vendor/bin/phpcs --standard=coding_standard.xml --ignore=vendor .

tests :
	$(MAKE) -C tests/ tests

coverage :
	$(MAKE) -C tests/ coverage
