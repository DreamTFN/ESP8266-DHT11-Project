# ESP8266 DHT11 Project

This project is designed to read temperature and humidity data from a DHT11 sensor connected to an ESP8266 and publish the data to a remote server every minute.

## Requirements

- ESP8266
- DHT11 Sensor
- Arduino IDE
- ESP8266WiFi library
- DHT library

## Setup

1. Connect the DHT11 sensor to your ESP8266 as follows:
   - VCC to 3.3V
   - GND to GND
   - Data to D6

2. Update the WiFi credentials in the `main.ino` file.

3. Upload the code to your ESP8266 using the Arduino IDE.

## Usage

The ESP8266 reads the temperature and humidity from the DHT11 sensor and sends the data to a remote server every minute. The MAC address of the ESP8266 is also printed to the serial monitor.

## License

This project is licensed under the MIT License.
