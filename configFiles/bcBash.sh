# Inserire qua l'id per l'utente
usr=1
while :
do
	v="$(gpio read 0)"
	if [ $v -eq 1 ]
	then
		curl 192.168.2.1/raspynelbosco/index.php?user=$usr
	fi
sleep 0.1
done