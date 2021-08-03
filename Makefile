list:
	grep -v -e "^\t" Makefile | grep . | grep -v "=" |  awk -F":.+?#" '{ print $$1 " #" $$2 }' | column -t -s '#'

install:
	composer install --no-interaction;

osx-build-local:
	make install;

osx-link-composer: # Re-link composer
	@echo "\033[92mLinking composer\033[0m";
	ln -fs $${HOME}/.strap/bin/composer2 $${HOME}/.strap/bin/composer

osx-link-php: # Re-link PHP for this project
	@echo "\033[92mUnlink brew managed php versions\033[0m";
	php-unlinker.sh
	@echo "\033[92mBuild and link dependencies\033[0m";
	brew bundle
	brew link --force --overwrite $(shell grep -Eo 'amp-php.{0,4}' Brewfile | head -1) | head -1

osx-local:
	sudo true;
	make osx-link-php osx-link-composer

osx-switch:
	make osx-link-php osx-link-composer
