# Css Filter Generator

This is a standalone WordPress plugin for development purpose ONLY.

This plugin is based in [CodePen](https://codepen.io/) [https://codepen.io/sosuke/pen/Pjoqqp](https://codepen.io/sosuke/pen/Pjoqqp) by [Barrett Sonntag](https://codepen.io/sosuke) and [Stack Overflow](https://stackoverflow.com/) discussion [How to transform black into any given color using only CSS filters](https://stackoverflow.com/questions/42966641/how-to-transform-black-into-any-given-color-using-only-css-filters).

It allows you to create css filter style for coloring icons.

For this plugin to work well the starting color needs to be black. The file format can be any format for graphics used on the web: png, jpg, gif, svg, etc, as long as the starting color is black. If your icon set isn't black you can prepend "brightness(0) saturate(100%)" to your filter property so it will first turn the icon set to black.

## Installation
You will need to set up a WordPress installation on local or online host.

Download this repository ZIP file.

In order to install and activate the plugin you have two options:

1- Unzip this file containing only one folder with the plugin, move this folder into the wp-content/plugins folder and activate the plugin.

2- Simply go to wp-admin menu Plugins, Add New, Upload plugin, select ZIP file you just downloaded, Install Now and Activate Plugin.

## Usage

Once it is installed you will find the plugin functionallity in wp-admin menu Tools, submenu CSS filter generator settings.

## TODO

Here is the list of things that I want to implement. Pull requests are welcome :)

- Add WP Dashboard Widget with this.
- Add c some kind of color picker.
