build: # build the container
	docker build . -t siesta
run: # run the app
	docker run --name siesta --detach --rm -p80:80 -v ~/projects/siesta:/var/www/html siesta
into: #enter the container
	docker exec -it apache_siesta bash
