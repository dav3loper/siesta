# Creating the host. Edit /etc/hosts and add the following line
 127.0.0.1       devel-siesta.com
# Creating vhost. vi /etc/apache2/sites-enabled/siesta.conf and add the following
```
<VirtualHost *:80>
    ServerAdmin admin@example.com
    ServerName devel-siesta.com

     DocumentRoot /home/dave/dave_repos/siesta/public
    <Directory /home/dave/dave_repos/siesta/public/>
       Options Indexes FollowSymLinks Includes ExecCGI
       AllowOverride All
       Require all granted
    </Directory>

    ErrorLog /var/log/apache2/devel-siesta.com
</VirtualHost>
```
# Install dependencies for the project
- Composer install

# Create database structure
- php artisan migrate

# Insert films to vote
- php artisan insert:films https://sitgesfilmfestival.com/cas/programa/pel_licules

# Access online form starting with the last film you have voted
- http://devel-siesta.com/movie/1