#!/bin/bash
# 24709|4046|Bridget Phillipson|Labour|Houghton and Sunderland South|Female|https://api20170418155059.azure-api.net/photo/vnbngj9b.jpeg?crop=CU_1:1&quality=80&download=false

#https://api.parliament.uk/Live/photo/S3bGSTqn.jpeg?crop=CU_1:1&quality=80&download=true

while read line
do
	mnid=`echo $line | awk -F"|" {'print $2'}`
	url=`echo $line | awk -F"|" {'print $7'}`
	filename=`echo $url | awk -F"/" {'print $5'} | awk -F"?" {'print $1'}`
	wget "https://api.parliament.uk/Live/photo/$filename?crop=CU_1:1&quality=80&download=true" -O assets/img/mps/$mnid.jpg --no-check-certificate
done < list.csv
