clear
echo "####################################################"
echo "# Raspy nel bosco                                  #"
echo "# V1.1                                             #"
echo "# Questo script installerà e configurerà i files   #"
echo "# necesari per il pulsante che avvierà il sistema  #"
echo "####################################################"
echo "# Copia del file bcBash"
cp configFiles/bcBash.sh /usr/bin/
echo "# Configurazione permessi"
chmod 777 /usr/bin/bcBash.sh
echo "# Copia del file rc.local"
mv /etc/rc.local /etc/rc.local.old
cp configFiles/rc.local_pulsante /etc/rc.local
chmod +x /etc/rc.local
echo "# Configurazione di raspynelbosco terminata!"
echo "# Sara possibile far partire le tracce ;)"