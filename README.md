
# Delivery Area with Google Maps - Wordpress Plugin

Welcome to the Delivery Area with Google Maps repository on Github. This plugin allows you create delivery areas in Google Maps and by a shortcode put it in everywhere. We recommend read the [Documentation Online](https://www.letsgodev.com/documentation/docs-delivery-area-with-google-maps/) to stay up to date about everything happening in the project. You can also [follow @agonzalesc](https://twitter.com/agonzalesc) on Twitter for the latest development updates.

## Requirements

- Minimum required PHP version is 5.6
- A [Google Maps Api Key](https://console.developers.google.com/flows/enableapi?apiid=maps_backend,geocoding_backend,directions_backend,distance_matrix_backend,elevation_backend,places_backend&keyType=CLIENT_SIDE&reusekey=true&hl=es)


## Installation

Go to : Plugins > Add New
![Alt text](https://www.letsgodev.com/wp-content/uploads/2015/08/install_plugin1.png "Add New Plugin")

Search "Delivery Area with Google Maps", install and activate
![Alt text](https://www.letsgodev.com/wp-content/uploads/2015/07/search_plugin.png "Install Plugin")


## Draw a Area Delivery

### Configure you Settings page

![Alt text](https://www.letsgodev.com/wp-content/uploads/2016/09/setting_page2.jpg "Configure you Settings page")

### Draw a polygon

![Alt text](https://www.letsgodev.com/wp-content/uploads/2016/09/add_new_area.jpg "Draw a polygon")

The ```address input``` is used to search for any location where you want to start creating your delivery area.
The ```color input``` is to paint the area delivery.

### Copy Shortcode and put it in a single page

![Alt text](https://www.letsgodev.com/wp-content/uploads/2016/09/shortcode3.jpg "Copy Shortcode and put it in a single page")

## How use the shortcode
```
[areamaps id=10712 w=100% h=400px]
```

- ```d``` : is the id of post (required).
- ```w``` is the widht ( you must specify the units: px, %, etc )
- ```h``` is the height ( you must specify the units: px, %, etc )