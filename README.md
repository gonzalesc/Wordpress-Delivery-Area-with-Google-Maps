
# Delivery Area with Google Maps - Wordpress Plugin

Welcome to the Delivery Area with Google Maps repository on Github. This plugin allows you create delivery areas in Google Maps and by a shortcode put it in everywhere.

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
- ```lib``` : yes/no , if it is "yes" then the Google Maps library is embed
- ```handle``` : is the name to be embed as JS library

## FAQ

**1. I have a warning "duplicate library"**
Maybe your theme or other plugin is embedding the Google Maps library so we should find out the handle name how this library is loaded.

After, you need add some parameters in your shortcode so the Google Maps library doesnt be embed.

```
[areamaps id=10712 w=100% h=400px lib="no" handle="name_library"]
```