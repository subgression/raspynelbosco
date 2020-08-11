clear
echo "####################################################"
echo "# Raspy nel bosco                                  #"
echo "# V1.1                                             #"
echo "# ATTENZIONE                                       #"
echo "# Nel caso di installazione di master              #"
echo "# il raspberry non sarà più collegabile            #"
echo "# alla rete perchè diventerà lui stesso un         #"
echo "# un router.                                       #"
echo "# Selezionare un opzione per la configurazione:    #"
echo "# 1. Installa un riproduttore:                     #"
echo "#    Installa un sistema standalone in grado di    #"
echo "#    riprodurre una singola traccia in stereofonia #"
echo "#    selezionare questa opzione solo in caso di    #"
echo "#    stazioni singole                              #"
echo "# 2. Installa un controller (master):              #"
echo "#    Installa un sistema in grado di controllare   #"
echo "#    la riproduzione di altri sistemi (slaves)     #"
echo "#    selezionare questo tipo di configurazione     #"
echo "#    solo nel caso di configurazioni a più canali  #"
echo "# 3. Installa uno slave:                           #"
echo "#    Installa uno slave controllato da un master   #"
echo "####################################################"
read -p "# Opzione: " opz
# CONFIGURAZIONE RIPRODUTORE
if [ $opz = "1" ]
then
  echo "# Update delle repository"
  apt-get update -y
  echo "# Installazione lamp"
  apt-get install apache2 php mysql-server php-mysql -y
  echo "# Copia della cartella raspynelbosco nella directory del web server"
  cp -rv raspynelbosco/ /var/www/html/
  echo "# Set up dei permessi della cartella raspynelbosco"
  chmod -R +rw /var/www/html/raspynelbosco/
  echo "# Configurazione variabili di Apache (il vecchio file sarà rinominato envvars.old)"
  mv /etc/apache2/envvars /etc/apache2/envvars.old
  cp configFiles/envvars /etc/apache2/envvars
  echo "# Installazione hostapd e dnsmasq"
  apt-get install hostapd dnsmasq -y
  systemctl stop dnsmasq
  systemctl stop hostapd
  echo "# Set up indirizzo statico"
  cp configFiles/dhcpcd.conf /etc/dhcpcd.conf
  echo "# Configurazione server DHCP"
  cp configFiles/dnsmasq.conf /etc/dnsmasq.conf
  read -p "Inserire il nome dell'access point: " nome_ap
  echo "# Configurazione access point"
  cp configFiles/hostapd.conf /etc/hostapd/hostapd.conf
  echo "ssid="$nome_ap >> /etc/hostapd/hostapd.conf
  mv /etc/default/hostapd /etc/default/hostapd.old
  cp configFiles/hostapd /etc/default/hostapd
  echo "# Attivazione servizi hostapd e dnsmasq"
  service dhcpcd restart
  systemctl unmask hostapd.service
  systemctl unmask dnsmasq.service
  systemctl start hostapd
  systemctl start dnsmasq
  echo "# Copia del file rc.local"
  mv /etc/rc.local /etc/rc.local.old
  cp configFiles/rc.local_master /etc/rc.local
  chmod +x /etc/rc.local
  echo "# Copia del corretto index.php (relativo al riproduttore)"
  rm /var/www/html/raspynelbosco/index.php
  cp configFiles/index_slave.php /var/www/html/raspynelbosco/
  mv /var/www/html/raspynelbosco/index_slave.php /var/www/html/raspynelbosco/index.php
  echo "# Aggiunta di tutti i permessi nella cartella raspynelbosco"
  chmod -R 777 /var/www/html/raspynelbosco/
  echo "# Configurazione di raspynelbosco terminata!"
  echo "# Sara possibile ora configurare le tracce ;)"
fi
# CONFIGURAZIONE CONTRROLLER
if [ $opz = "2" ]
then
  echo "# Update delle repository"
  apt-get update -y
  echo "# Installazione lamp"
  apt-get install apache2 php mysql-server php-mysql -y
  echo "# Copia della cartella raspynelbosco nella directory del web server"
  cp -rv raspynelbosco/ /var/www/html/
  echo "# Set up dei permessi della cartella raspynelbosco"
  chmod -R +rw /var/www/html/raspynelbosco/
  echo "# Configurazione variabili di Apache (il vecchio file sarà rinominato envvars.old)"
  mv /etc/apache2/envvars /etc/apache2/envvars.old
  cp configFiles/envvars /etc/apache2/envvars
  echo "# Installazione hostapd e dnsmasq"
  apt-get install hostapd dnsmasq -y
  systemctl stop dnsmasq
  systemctl stop hostapd
  echo "# Set up indirizzo statico"
  cp configFiles/dhcpcd.conf /etc/dhcpcd.conf
  echo "# Configurazione server DHCP"
  cp configFiles/dnsmasq.conf /etc/dnsmasq.conf
  read -p "Inserire il nome dell'access point: " nome_ap
  echo "# Configurazione access point"
  cp configFiles/hostapd.conf /etc/hostapd/hostapd.conf
  echo "ssid="$nome_ap >> /etc/hostapd/hostapd.conf
  mv /etc/default/hostapd /etc/default/hostapd.old
  cp configFiles/hostapd /etc/default/hostapd
  echo "# Attivazione servizi hostapd e dnsmasq"
  service dhcpcd restart
  systemctl unmask hostapd.service
  systemctl unmask dnsmasq.service
  systemctl start hostapd
  systemctl start dnsmasq
  echo "# Copia del file rc.local"
  mv /etc/rc.local /etc/rc.local.old
  cp configFiles/rc.local_master /etc/rc.local
  chmod +x /etc/rc.local
  echo "# Copia del corretto index.php (relativo al master)"
  rm /var/www/html/raspynelbosco/index.php
  cp configFiles/index_master.php /var/www/html/raspynelbosco/
  mv /var/www/html/raspynelbosco/index_master.php /var/www/html/raspynelbosco/index.php
  echo "# Aggiunta di tutti i permessi nella cartella raspynelbosco"
  chmod -R 777 /var/www/html/raspynelbosco/
  echo "# Configurazione di raspynelbosco terminata!"
  echo "# Sara possibile ora configurare le tracce ;)"
fi
# CONFIGURAZIONE SLAVE
if [ $opz = "3" ]
then
  echo "# Update delle repository"
  apt-get update -y
  echo "# Installazione lamp"
  apt-get install apache2 php mysql-server php-mysql -y
  echo "# Copia della cartella raspynelbosco nella directory del web server"
  cp -rv raspynelbosco/ /var/www/html/
  echo "# Set up dei permessi della cartella raspynelbosco"
  chmod -R +rw /var/www/html/raspynelbosco/
  echo "# Configurazione variabili di Apache (il vecchio file sarà rinominato envvars.old)"
  mv /etc/apache2/envvars /etc/apache2/envvars.old
  cp configFiles/envvars /etc/apache2/envvars
  echo "# Copia del file rc.local"
  mv /etc/rc.local /etc/rc.local.old
  cp configFiles/rc.local_slave /etc/rc.local
  chmod +x /etc/rc.local
  echo "# Copia del corretto index.php (relativo allo slave)"
  rm /var/www/html/raspynelbosco/index.php
  cp configFiles/index_slave.php /var/www/html/raspynelbosco/
  mv /var/www/html/raspynelbosco/index_slave.php /var/www/html/raspynelbosco/index.php
  echo "# Aggiunta di tutti i permessi nella cartella raspynelbosco"
  chmod -R 777 /var/www/html/raspynelbosco/
  echo "# Configurazione di raspynelbosco terminata!"
  echo "# Sara possibile ora configurare le tracce ;)"
fi