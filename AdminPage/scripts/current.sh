#!/bin/bash  

#This script returns the current network settings
#
# Usage: sudo sh current.sh  [options]
# 
# [options]
# -i,--interface [wlan0,wlan1]    Print interface  
# -s,--ssid                       Print name of your WIFI network
# -c,--channel                    Print channel number
# -p,--password                   Print WiFi password
# -m,--mode                       Print MAZI zone mode

usage() { echo "Usage: sudo sh current.sh  [options]" 
          echo " " 
          echo "[options]" 
          echo "-i,--interface [wlan0,wlan1]    Print the interface name"
          echo "-s,--ssid                       Print the name of your WIFI network"
          echo "-c,--channel                    Print the number of channel"
          echo "-p,--password                   Print the WiFi password"
          echo "-m,--mode                       Print the network mode" 1>&2; exit 1; }


while [ $# -gt 0 ]
do
key="$1"

case $key in
    -i |--interface)
    INTERFACE="YES"
    ;;
    -s|--ssid)
    SSID="YES"
    ;;
    -c|--channe)
    CHANNEL="YES"
    ;;
    -p|--password)
    PASSWORD="YES"
    ;;
	-m|--mode)
    MODE="YES"
    ;;
    *)
       # unknown option
    usage   
    ;;
esac
shift #past argument
done

## print interface
if [ "$INTERFACE" = "YES" ]; then
  echo "interface $(grep 'interface' /etc/hostapd/hostapd.conf| sed 's/\interface=//g') "  
    
fi

## print channel
if [ "$CHANNEL" = "YES" ]; then
   
   echo "channel $(grep 'channel' /etc/hostapd/hostapd.conf| sed 's/channel=//g')" 
fi

## print ssid
if [ "$SSID" = "YES" ]; then
    
  echo "ssid $(grep 'ssid' /etc/hostapd/hostapd.conf| sed 's/ssid=//g')"  
fi

## print password if it exists
if [ "$PASSWORD" = "YES" ]; then
   if [ "$(grep 'wpa_passphrase' /etc/hostapd/hostapd.conf| sed 's/wpa_passphrase=//g')" ];then
     echo "password $(grep 'wpa_passphrase' /etc/hostapd/hostapd.conf| sed 's/wpa_passphrase=//g')"
   else 
     echo "password"
  fi 
fi

## print mode
if [ "$MODE" = "YES" ]; then
  echo "mode"
fi
