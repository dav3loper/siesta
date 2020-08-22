build: # build the container
	docker build . -t siesta
run: # run the app
	docker run --name siesta --detach --rm -p80:80 siesta; docker exec -it siesta sh -c "composer install --prefer-dist"
