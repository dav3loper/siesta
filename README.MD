# Creating the host. Edit /etc/hosts and add the following line
 127.0.0.1       dev-siesta.com
# Creating vhost. vi /etc/apache2/sites-enabled/siesta.conf and add the following
```
<VirtualHost *:80>
    ServerAdmin admin@example.com
    ServerName dev-siesta.com

     DocumentRoot /home/dave/workspace/siesta/public
    <Directory /home/dave/workspace/siesta/public/>
       Options Indexes FollowSymLinks Includes ExecCGI
       AllowOverride All
       Require all granted
    </Directory>

    ErrorLog /var/log/apache2/dev-siesta.com
</VirtualHost>
```
# Install dependencies for the project
- Composer install

# Create database structure
- php artisan migrate

# Insert films to vote
- Edit InsertSitgesFilm to increase film festival id (i know... some day...)
- php artisan insert:films https://sitgesfilmfestival.com/cas/programa/pel_licules?filmsperpage=250

# Access online form starting with the last film you have voted
- http://dev-siesta.com/movie/1

##Docker

There are included docker files to raise application without the required infrastructure

# To raise application
docker-compose up -d

#You should access via port 8080
http://dev-siesta.com:8080/

