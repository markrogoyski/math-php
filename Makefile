.PHONY : coverage lint tests

all : lint tests coverage

lint :
	find src tests -name \*.php | xargs phpcs --standard=coding_standard.xml

tests :
	$(MAKE) -C tests/ tests

coverage :
	$(MAKE) -C tests/ coverage
