#!/usr/bin/python
# Copyright (c) 2015 Antti Minkkinen
import Adafruit_BMP.BMP085 as BMP085
import Adafruit_DHT
import MySQLdb

db = MySQLdb.connect(host="192.168.0.1",
					 port=1194,
                     user="weather",
                      passwd="weather123",
                      db="weather")

cur = db.cursor() 

# BMP180
sensor = BMP085.BMP085()
temperature1 = sensor.read_temperature()
pressure1 = sensor.read_pressure()

# DHT11
humidity2, temperature2 = Adafruit_DHT.read_retry(Adafruit_DHT.DHT11, 4)


# Write data to database
sql1 = "INSERT INTO weather.measurement (sensorId, time, temperature, humidity, pressure) VALUES(1, NOW(), "+str(temperature1)+", 0, "+str(pressure1)+")"
sql2 = "INSERT INTO weather.measurement (sensorId, time, temperature, humidity, pressure) VALUES(2, NOW(), "+str(temperature2)+", "+str(humidity2)+", 0)"

try:
	cur.execute(sql1);
	cur.execute(sql2);
	db.commit()
except:
	db.rollback()