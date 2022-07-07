# css-filter-generator

This is a standalone WordPress plugin for developement purpose ONLY.

This plugin is based in [CodePen](https://codepen.io/) [https://codepen.io/sosuke/pen/Pjoqqp](https://codepen.io/sosuke/pen/Pjoqqp) by [Barrett Sonntag](https://codepen.io/sosuke) and [Stack Overflow](https://stackoverflow.com/) discussion [How to transform black into any given color using only CSS filters](https://stackoverflow.com/questions/42966641/how-to-transform-black-into-any-given-color-using-only-css-filters).

It allows you to create css filter style for coloring icons.

For this plugin to work well the starting color needs to be black. The file format can be any format for graphics used on the web: png, jpg, gif, svg, etc, as long as the starting color is black. If your icon set isn't black you can prepend "brightness(0) saturate(100%)" to your filter property so it will first turn the icon set to black.

## Installation
You will need to set up a local WordPress installation on localhost in order for the license to be valid.

Simply move the example-plugin.php script into the wp-content/plugins folder and activate the plugin.

Once it is installed you will find the plugin functionallity in wp-admin menu Tools, submenu CSS filter generator settings.

## Usage

## TODO

Here is the list of things that I want to implement. Pull requests are welcome :)

- Add