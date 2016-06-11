# Generador de la aplicación
# Basado en https://github.com/cakephp/cakephp/blob/master/Makefile

# Las siguientes variables de entorno deben establecerse
# - VERSION
# - GITHUB_USER
# - GITHUB_PASS
# - GITHUB_TOKEN (opcional si se utiliza doble factor de autenticación)

# Ajustes de la aplicación
APP_NAME=srat
APP_TITLE=Sistema de Registro de Asistencia y Temas

# Ajustes de GitHub
API_HOST=https://api.github.com
OWNER=UTN-FRD
REMOTE=origin
REPO=srat
UPLOAD_HOST=https://uploads.github.com

ifdef GITHUB_TOKEN
	AUTH=-H 'Authorization: token $(GITHUB_TOKEN)'
else
	AUTH=-u $(GITHUB_USER) -p$(GITHUB_PASS)
endif

# Rama actual
CURRENT_BRANCH=$(shell git branch | grep '*' | tr -d '* ')

# Número versión
DASH_VERSION=$(shell echo $(VERSION) | sed -e s/\\./-/g)

# Utilizar el número de versión para averiguar si el lanzamiento es un pre-lanzamiento
PRERELEASE=$(shell echo $(VERSION) | grep -E 'dev|rc|alpha|beta' --quiet && echo 'true' || echo 'false')

# Tarea predeterminada
.DEFAULT: help
ALL: help

# Tareas ficticias
.PHONY: help clean

# Ayuda
help:
	@echo "$(APP_TITLE)"
	@echo "-----------------------------------------"
	@echo ""
	@echo "clean"
	@echo "  Elimina archivos generados por esta aplicación."
	@echo ""
	@echo "build"
	@echo "  Genera la aplicación. Requiere el parámetro VERSION."
	@echo ""
	@echo "publish"
	@echo "  Publica un borrador de lanzamiento y GitHub junto con el archivo zip de la aplicación. Requiere los parámetros"
	@echo "  VERSION, GITHUB_USER y/o GITHUB_TOKEN."
	@echo ""
	@echo "release"
	@echo "  Atajo para ejecutar las tareas 'build' y 'publish'."
	@echo ""


# Tarea de utilidad para comprobar parámetros requeridos
guard-%:
	@if [ "$($*)" = '' ]; then \
		echo "Falta la variable requerida '$*'."; \
		exit 1; \
	fi;


# Actualiza VERSION.txt a una nueva versión
bump-version: guard-VERSION
	@echo "Actualizando VERSION.txt to $(VERSION)"
	sed -i s'/^[0-9]\.[0-9]\.[0-9].*/$(VERSION)/' VERSION.txt
	git add VERSION.txt
	git commit -S -m "Actualizado número de versión a $(VERSION)"


# Etiqueta un lanzamiento
tag-release: guard-VERSION bump-version
	@echo "Etiquetando $(VERSION)"
	git tag -s $(VERSION) -m "$(APP_TITLE) $(VERSION)"
	git push --tags $(REMOTE) $(CURRENT_BRANCH)


# Limpieza
clean:
	rm -rf ./build/*.zip
	rm -rf ./build/package
	rm -rf ./build/tmp

	mkdir -p ./build/package
	mkdir -p ./build/tmp


# Genera la aplicación
build: guard-VERSION clean
	@echo "Extrayendo copia de la aplicación..."
	git checkout-index -a -f --prefix=./build/tmp/


	@echo "Instalando dependencias de la aplicación..."
	cd ./build/tmp && \
	wget -nv -qO- https://getcomposer.org/installer | php && \
	php composer.phar install --no-dev --no-autoloader


	@echo "Preparando CakePHP..."
	rm -rf ./build/tmp/vendor/cakephp/cakephp/lib/Cake/Test
	rm -rf ./build/tmp/vendor/cakephp/cakephp/lib/Cake/TestSuite

	mv ./build/tmp/vendor/cakephp/cakephp/lib ./build/package/lib


	@echo "Preparando archivos de la aplicación..."
	rm -rf \
		./build/tmp/app/Config/acl.* \
		./build/tmp/app/Config/core.php.default \
		./build/tmp/app/Config/email.* \
		./build/tmp/app/Config/Schema/db_acl.* \
		./build/tmp/app/Config/Schema/i18n.* \
		./build/tmp/app/Config/Schema/sessions.* \
		./build/tmp/app/Locale/eng \
		./build/tmp/app/Plugin/CakePdf/*.json \
		./build/tmp/app/Plugin/CakePdf/*.md \
		./build/tmp/app/Plugin/CakePdf/.travis.yml \
		./build/tmp/app/Plugin/CakePdf/Test \
		./build/tmp/app/Plugin/CakePdf/Vendor \
		./build/tmp/app/Plugin/Search/*.json \
		./build/tmp/app/Plugin/Search/*.md \
		./build/tmp/app/Plugin/Search/*.txt \
		./build/tmp/app/Plugin/Search/.semver \
		./build/tmp/app/Plugin/Search/.travis.yml \
		./build/tmp/app/Plugin/Search/Docs \
		./build/tmp/app/Plugin/Search/Locale/*.pot \
		./build/tmp/app/Plugin/Search/Locale/deu \
		./build/tmp/app/Plugin/Search/Locale/fre \
		./build/tmp/app/Plugin/Search/Locale/por \
		./build/tmp/app/Plugin/Search/Locale/rus \
		./build/tmp/app/Plugin/Search/Test \
		./build/tmp/app/Test \
		./build/tmp/app/tmp/tests \
		./build/tmp/app/View/Emails \
		./build/tmp/app/View/Layouts/ajax.ctp \
		./build/tmp/app/View/Layouts/Emails \
		./build/tmp/app/View/Layouts/error.ctp \
		./build/tmp/app/View/Layouts/flash.ctp \
		./build/tmp/app/View/Layouts/js \
		./build/tmp/app/View/Layouts/rss \
		./build/tmp/app/View/Layouts/xml \
		./build/tmp/app/View/Scaffolds \
		./build/tmp/app/webroot/css/cake.generic.css \
		./build/tmp/app/webroot/favicon.ico \
		./build/tmp/app/webroot/files \
		./build/tmp/app/webroot/img/*.gif \
		./build/tmp/app/webroot/img/*.png \
		./build/tmp/app/webroot/test.php

	touch ./build/tmp/app/webroot/favicon.ico

	mv ./build/tmp/app/Config/database.php.default ./build/tmp/app/Config/database.php


	@echo "Optimizando hojas de estilos en cascada..."
	find ./build/tmp/app/webroot/css -type f -name "*.css" -not -name "*.min.css" -exec yuglify -w -s {} \;
	cat \
		./build/tmp/app/webroot/css/bootstrap.min.css \
		./build/tmp/app/webroot/css/select2.min.css \
		./build/tmp/app/webroot/css/layout.css \
		./build/tmp/app/webroot/css/notify.css \
		./build/tmp/app/webroot/css/form.css \
		./build/tmp/app/webroot/css/table.css \
		> ./build/tmp/app/webroot/css/style.css

	rm \
		./build/tmp/app/webroot/css/bootstrap.min.css \
		./build/tmp/app/webroot/css/select2.min.css \
		./build/tmp/app/webroot/css/layout.css \
		./build/tmp/app/webroot/css/notify.css \
		./build/tmp/app/webroot/css/form.css \
		./build/tmp/app/webroot/css/table.css


	@echo "Optimizando scripts..."
	find ./build/tmp/app/webroot/js -type f -name "*.js" -not -name "*.min.js" -exec yuglify -w -s {} \;
	cat \
		./build/tmp/app/webroot/js/jquery.min.js \
		./build/tmp/app/webroot/js/bootstrap.min.js \
		./build/tmp/app/webroot/js/select2.min.js \
		./build/tmp/app/webroot/js/select2_locale_es.min.js \
		./build/tmp/app/webroot/js/app.js \
		./build/tmp/app/webroot/js/form.js \
		./build/tmp/app/webroot/js/table.js \
		> ./build/tmp/app/webroot/js/script.js

	rm \
		./build/tmp/app/webroot/js/jquery.min.js \
		./build/tmp/app/webroot/js/bootstrap.min.js \
		./build/tmp/app/webroot/js/select2.min.js \
		./build/tmp/app/webroot/js/select2_locale_es.min.js \
		./build/tmp/app/webroot/js/app.js \
		./build/tmp/app/webroot/js/form.js \
		./build/tmp/app/webroot/js/table.js


	@echo "Actualizando archivos..."
	mv ./build/tmp/app ./build/package/app

	cp -t ./build/package ./build/tmp/.htaccess ./build/tmp/index.php ./build/tmp/LICENCIA.txt ./build/tmp/VERSION.txt
	cp -ra ./build/skel/* ./build/package

	find ./build/package -type f -name "empty" -delete

	@echo "Generando zipball..."
	cd ./build/package && find . | zip -q ../$(APP_NAME)-$(DASH_VERSION).zip -@


# Publica un lanzamiento en GitHub
publish: guard-VERSION guard-GITHUB_USER build
	@echo "Creando borrador de lanzamiento para $(VERSION). prerelease=$(PRERELEASE)"
	curl $(AUTH) -XPOST $(API_HOST)/repos/$(OWNER)/$(REPO)/releases \
	-d '{ "tag_name": "$(VERSION)", "name": "$(APP_TITLE) $(VERSION)", "draft": true, "prerelease": $(PRERELEASE) }' \
	 > release.json

	php -r '$$f = file_get_contents("./release.json"); $$d = json_decode($$f, true); file_put_contents("./id.txt", $$d["id"]);'

	@echo "Subiendo zipball a GitHub..."
	curl $(AUTH) -XPOST \
		$(UPLOAD_HOST)/repos/$(OWNER)/$(REPO)/releases/`cat ./id.txt`/assets?name=$(APP_NAME)-$(DASH_VERSION).zip \
		-H "Accept: application/vnd.github.manifold-preview" \
		-H 'Content-Type: application/zip' \
		--data-binary '@./build/$(APP_NAME)-$(DASH_VERSION).zip'

	rm release.json
	rm id.txt


# Atajo para hacer un lanzamiento
release: guard-VERSION guard-GITHUB_USER tag-release clean build publish
