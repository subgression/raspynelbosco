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
echo "# Si vuole configurare un Access Point per questo raspberry?  [Da utilizzare solo nel caso di master, gli slaves non dovranno avere access point]"
read -p "Configurare Access Point (S/N):" a
if [ $a = "S" ]
then
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
  systemctl start hostapd
  systemctl start dnsmasq
fi
echo "# Configurazione di raspynelbosco terminata!"
echo "# Sara possibile ora configurare le tracce ;)"
