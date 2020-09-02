build: # build the container
	docker build . -t siesta
run: # run the app
	docker run --name siesta --detach --rm -p80:80 -v ~/repos/siesta:/var/www/html siesta; docker exec -it siesta sh -c "composer install --prefer-dist"
into: #enter the container
	docker exec -it apache_siesta bash
