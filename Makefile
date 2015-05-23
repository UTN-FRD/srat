# Generador de la aplicación

# Las siguientes variables de entorno deben establecerse
# - VERSION

# Ajustes de la aplicación
APP_NAME=srat
APP_TITLE=Sistema de Registro de Asistencia y Temas

# Ajustes de GitHub
REMOTE=origin

# Rama actual
CURRENT_BRANCH=$(shell git branch | grep '*' | tr -d '* ')

# Tarea predeterminada
.DEFAULT: help
ALL: help

# Tareas ficticias
.PHONY: help

# Ayuda
help:
	@echo "$(APP_TITLE)"
	@echo "-----------------------------------------"
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
	git commit -m "Actualizado número de versión a $(VERSION)"


# Etiqueta un lanzamiento
tag-release: guard-VERSION bump-version
	@echo "Etiquetando $(VERSION)"
	git tag -a $(VERSION) -m "$(APP_TITLE) $(VERSION)"
	git push --tags $(REMOTE) $(CURRENT_BRANCH)
