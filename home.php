<?php
session_start();
if ($_SESSION['logged'] === false || $_SESSION['logged'] === NULL) {
    header("Location: index.php");
    exit;
}
?>
<!doctype html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
    <!-- Google Fonts -->
    <link type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet"
    />
    <link type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.min.css" rel="stylesheet"/>
    <title>Relazione AWS</title>
</head>
<body>
<!-- As a heading -->
<nav class="navbar navbar-light bg-body-tertiary">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">Relazione AWS</span>
        <span class="input-group-text border-0" id="logout_addon">
            <a href="logout.php" style="text-decoration: none;"> <i class="fas fa-sign-out"></i> </a>
        </span>
    </div>
</nav>
<main>
    <br>
    <br>
    <br>
    <br>
    <article>
        <h2>Relazione AWS</h2>
        <ol>
            <li><a href="#configurazione-aws">Configurazione AWS</a></li>
            <li><a href="#configurazione-macchina-linux">Configurazione del server</a></li>
        </ol>
        <br>
        <br>
        <br>
        <br>
        <div class="container-fluid">
            <section id="configurazione-aws">
                <h3>Configurazione AWS</h3>
                <p>Come primo step per la realizzazione del sito dobbiamo procedere a creare una istanza cloud AWS EC2.</p>
                <p>Procediamo dunque ad accedere ad accedere a <a href="https://awsacademy.instructure.com/login/canvas">Amazon AWS Canvas Academy</a>
                    e accediamo con le nostre credenziali.
                </p>
                <p>Successivamente ci verrà presentata la pagina di accesso ai moduli forniti dall'academy e scegliamo quello fornito.</p>
                <p>Clicchiamo sul link modules in basso a sinistra della pagina, nella zona Get Started</p>
                <img src="assets/images/modules.png" alt="pagina">
                <p>Clicchiamo sul link launch AWS academy learner LAB</p>
                <img src="assets/images/academy_learner_lab.png" alt="pagina">
                <p>Una volta caricata la pagina, clicchiamo sul pulsante start e quando il laboratorio si è avviato cliccare su AWS</p>
                <img src="assets/images/launch_lab.png" alt="pagina">
                <p>Successivamente una volta caricata la pagina della console, andiamo nella sezione crea una soluzione e scegliamo crea una macchina virtuale.</p>
                <img src="assets/images/EC2.png" alt="pagina">
                <p>Nella pagina di creazione della macchina virtuale impostiamo un nome, il sistema operativo ubuntu, creiamo una coppia di chiavi per connetterci con openSSH o Putty e impostiamo le regole di accesso.</p>
                <p>Una volta finito clicchiamo su avvia istanza</p>
                <p>Successivamente Andiamo nel pannello di controllo E2C, clicchiamo su istanze (in esecuzione), scegliamo la macchina appena creata, andiamo nella sezione sicurezza e aggiungiamo le regole mancanti</p>
                <img src="assets/images/regole_entrata.png" alt="">
            </section>
            <section id="configurazione-macchina-linux">
                <h3>Configurazione server</h3>
                <p>Per collegarci alla macchina AWS appena creata possiamo utilizzare openSSH o PUTTY e le indicazioni fornite da AWS stesso.</p>
                <p>Come prima cosa aggiorniamo la macchina LINUX:</p>
                <pre>
                    <code>
                        sudo apt update
                    </code>
                    <code>
                        sudo apt upgrade
                    </code>
                </pre>
                <p>Successivamente installiamo docker e docker-compose, per permettere la comunicazione tra container diversi</p>
                <pre>
                    <code>
                        sudo apt install apt-transport-https ca-certificates curl gnupg lsb-release
                    </code>
                    <code>
                        curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
                    </code>
                    <code>
                        echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
                    </code>
                    <code>
                        sudo apt update
                    </code>
                    <code>
                        sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-linux-$(uname -m)" -o /usr/local/bin/docker-compose
                    </code>
                    <code>
                        sudo chmod +x /usr/local/bin/docker-compose
                    </code>
                    <code>
                        sudo apt install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
                    </code>
                </pre>
                <p>Successivamente procediamo alla creazione del project folder per la configurazione</p>
                <pre>
                    <code>
                        mkdir lamp
                    </code>
                    <code>
                        mkdir lamp/{apache_docker,php_docker,www}
                    </code>
                    <code>
                        cd lamp
                        vim docker-compose.yaml
                    </code>
                </pre>
                <p>Codice file docker-compose.yaml</p>
                <pre>
                    <code>
                        version: "3.8"
                        services:
                          # PHP Service
                          php:
                            build: './php_docker/'
                            volumes:
                              - ./www/:/var/www/html/
                          # Apache Service
                          apache:
                            build: './apache_docker/'
                            depends_on:
                              - php
                            ports:
                              - "80:80"
                              - "443:443"
                            volumes:
                              - ./www/:/var/www/html/
                          # MariaDB Service
                          mariadb:
                            image: mariadb:10.11
                            ports:
                              - "3306:3306"
                            environment:
                              MYSQL_ROOT_PASSWORD: your_password
                              MYSQL_USER: your_user
                            volumes:
                              - mysqldata:/var/lib/mysql
                          # phpMyAdmin Service
                          phpmyadmin:
                            image: phpmyadmin/phpmyadmin:latest
                            ports:
                              - 8080:80
                            environment:
                              PMA_HOST: mariadb
                            depends_on:
                              - mariadb
                        # Volumes
                        volumes:
                          mysqldata:
                    </code>
                </pre>
                <p>Codice del dockerfile di php_docker</p>
                <pre>
                    <code>
                        vim php_docker/Dockerfile
                    </code>
                    <code>
                        FROM php:8.2-fpm
                        # Installing dependencies for building the PHP modules
                        RUN apt update && \
                            apt install -y zip libzip-dev libpng-dev libicu-dev libxml2-dev
                        # Installing additional PHP modules
                        RUN docker-php-ext-install mysqli pdo pdo_mysql gd zip intl xml
                        # Cleaning APT cache
                        RUN apt clean
                    </code>
                </pre>
                <p>Codice del file apache-vhost.conf della cartella apache_docker</p>
                <pre>
                    <code>
                        vim apache_docker/apache-vhost.conf
                    </code>
                    <code>
                        # Specify the domain name or the ip of the server.
                        ServerName localhost

                        # The first 3 LoadModule statements are for the proxy to the PHP container.
                        LoadModule deflate_module /usr/local/apache2/modules/mod_deflate.so
                        LoadModule proxy_module /usr/local/apache2/modules/mod_proxy.so
                        LoadModule proxy_fcgi_module /usr/local/apache2/modules/mod_proxy_fcgi.so

                        # Accept any ipv4 or ipv6 address on port 443 (HTTPS)
                        <VirtualHost *:443>
                            # Proxy .php requests to port 9000 of the PHP container
                            ProxyPassMatch ^/(.*\.php(/.*)?)$ fcgi://php:9000/var/www/html/$1
                            DocumentRoot /var/www/html/
                            <Directory /var/www/html/>
                                DirectoryIndex index.php
                                Options Indexes FollowSymLinks
                                AllowOverride All
                                Require all granted
                            </Directory>
                            # Send Apache logs to stdout and stderr
                            CustomLog /proc/self/fd/1 common
                            ErrorLog /proc/self/fd/2
                        '</VirtualHost>'
                    </code>
                </pre>
                <p>Codice del file Dockerfile della cartella apache_docker</p>
                <pre>
                    <code>
                        vim apache_docker/Dockerfile
                    </code>
                    <code>
                        # Apache docker container https://hub.docker.com/_/httpd .
                        # This image is based on the popular Alpine Linux project,
                        # a security-oriented, lightweight Linux distribution based on musl libc and busybox.
                        # https://alpinelinux.org/
                        FROM httpd:2.4-alpine
                        RUN apk update; apk upgrade;
                        # PHP docker container that has apache.
                        FROM php:8.2-apache
                        # Prepare apt
                        RUN apt-get update
                        # Install self-signed SSL certificate
                        RUN apt-get install -y ssl-cert
                        # Enable Apache2 mod_ssl for abilitate SSL
                        RUN a2enmod ssl
                        # Enable Apache2 mod_rewrite for redirection from HTTP to HTTPS
                        RUN a2enmod rewrite
                        # Setup Apache2 HTTPS environment
                        RUN a2ensite default-ssl.conf
                        # Set working directory
                        WORKDIR /var/www/html
                        # Installing dependencies for building the PHP modules
                        RUN apt update && \
                            apt install -y zip libzip-dev libpng-dev libicu-dev libxml2-dev

                        # Installing additional PHP modules
                        RUN docker-php-ext-install mysqli pdo pdo_mysql gd zip intl xml

                        # Copy Apache virtual host file to proxy .php requests to PHP container
                        COPY apache-vhost.conf /usr/local/apache2/conf/apache-vhost.conf
                        RUN echo "Include /usr/local/apache2/conf/apache-vhost.conf" \
                            >> /usr/local/apache2/conf/httpd.conf
                    </code>
                </pre>
                <p>Codice del file .htaccess della cartella www</p>
                <pre>
                    <code>
                        vim www/.htaccess
                    </code>
                    <code>
                        # This file is the configuration file that does the redirection from HTTP to HTTPS.
                        RewriteEngine On
                        RewriteCond %{HTTPS} off
                        # I define a rule that redirects all the unsecured connections in HTTPS
                        RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
                    </code>
                </pre>
                <p>Codice per avviare i container</p>
                <pre>
                    <code>
                        docker-compose up -d
                    </code>
                </pre>
            </section>
        </div>
    </article>
</main>
<footer class="bg-body-tertiary text-center text-lg-start fixed-bottom">
    <!-- Copyright -->
    <div class="text-center p-3">
        © 2020 Copyright:
        <a class="text-body" href="https://mdbootstrap.com/">MDBootstrap.com</a>
    </div>
    <!-- Copyright -->
</footer>
<!-- MDB -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.umd.min.js"
></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
