.PHONY : coverage lint tests

all : lint tests coverage

lint :
	phpcs --standard=coding_standard.xml --ignore=vendor .

tests :
	$(MAKE) -C tests/ tests

coverage :
	$(MAKE) -C tests/ coverage
