#!/usr/bin/python
# Copyright (c) 2015 Antti Minkkinen
import Adafruit_BMP.BMP085 as BMP085
import Adafruit_DHT
import MySQLdb

db = MySQLdb.connect(host="192.168.0.1:1194", # your host, usually localhost
                     user="weather", # your username
                      passwd="weather123", # your password
                      db="weather") # name of the data base

# you must create a Cursor object. It will let
#  you execute all the queries you need
cur = db.cursor() 

# BMP180
sensor = BMP085.BMP085()
temperature1 = sensor.read_temperature()
pressure1 = sensor.read_pressure()

# DHT11
humidity2, temperature2 = Adafruit_DHT.read_retry(Adafruit_DHT.DHT11, 4)

# Use all the SQL you like
cur.execute("INSER INTO measurement(sensorId, time, temperature, humidity, pressure) VALUES(1, NOW(), "+temperature1+", 0.0, "+pressure1+")");
cur.execute("INSER INTO measurement(sensorId, time, temperature, humidity, pressure) VALUES(2, NOW(), "+temperature2+", "+humidity2+", 0.0)");
