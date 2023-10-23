<?php
/*
Plugin Name: CSS filter generator
Plugin URI: https://github.com/qbreis/css-filter-generator
Description: CSS filter generator to convert from black to target hex color.
Author: Enric Gatell
Version: 1.0
Author URI: https://github.com/qbreis
*/
class cssfiltergenerator_Plugin {
    public function __construct() {
      	// Hook into the admin menu
      	add_action( 'admin_menu', array( $this, 'create_plugin_settings_page' ) );
    }
    public function create_plugin_settings_page() {
        $plugin_data = get_file_data(__FILE__, array('Version' => 'Version'), false);
        $plugin_version = $plugin_data['Version'];

      	// Add the menu item and page
      	$page_title = 'CSS filter generator settings';
      	$menu_title = 'CSS filter generator';//v3 ('.$plugin_version.')';
      	$capability = 'manage_options';
      	$slug = 'cssfiltergenerator';
      	$callback = array( $this, 'plugin_settings_page_content' );
      	//$icon = 'dashicons-admin-plugins';
        $icon = plugin_dir_url( __FILE__ ).'images/cssfiltergenerator-logo.png';
      	$position = 100;

      	//add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );

        add_submenu_page(
            'tools.php',
            $page_title,
            $page_title,
            $capability,
            $slug,
            $callback
        );

    }
    public function plugin_settings_page_content() {
        $plugin_data = get_file_data(__FILE__, array('Version' => 'Version'), false);
        $plugin_version = $plugin_data['Version'];

        if( $_POST['updated'] === 'true' ){
            $this->handle_form();
        } ?>
    	  <div class="wrap">
            <h2>CSS filter generator (v<?=$plugin_version?>)</h2>

            <table>
                <tr valign="top">
                    <td width="50%">
                        <fieldset>
                            <p>
                                <label>Target color</label> <input id="target-color" class="target" type="text" placeholder="target hex" value="#00a4d6"/>
                            </p>
                            <button class="execute">Compute Filters</button>
                        </fieldset>
                        <p>Real pixel, color applied through CSS <code>background-color</code>:</p>
                        <div id="realPixel" class="pixel realPixel"></div>

                        <p>Filtered pixel, color applied through CSS <code>filter</code>:</p>
                        <div id="filterPixel" class="pixel filterPixel"></div>

                        <p id="filterDetail"></p>
                        <p id="lossDetail"></p>
                    </td>
                    <td>
                        <p>This is a standalone WordPress plugin for development purpose ONLY.</p>
                        <p>This plugin is based in <a href="https://codepen.io/" rel="nofollow">CodePen</a> <a href="https://codepen.io/sosuke/pen/Pjoqqp" rel="nofollow">https://codepen.io/sosuke/pen/Pjoqqp</a> by <a href="https://codepen.io/sosuke" rel="nofollow">Barrett Sonntag</a> and <a href="https://stackoverflow.com/" rel="nofollow">Stack Overflow</a> discussion <a href="https://stackoverflow.com/questions/42966641/how-to-transform-black-into-any-given-color-using-only-css-filters" rel="nofollow">How to transform black into any given color using only CSS filters</a>.</p>
                        <p>It allows you to create css filter style for coloring icons.</p>
                        <p>For this plugin to work well the starting color needs to be black. The file format can be any format for graphics used on the web: png, jpg, gif, svg, etc, as long as the starting color is black. If your icon set isn't black you can prepend "brightness(0) saturate(100%)" to your filter property so it will first turn the icon set to black.</p>
                    </td>
                </tr>
            </table>
        </div><!-- .wrap -->
<style>
.pixel {
    display: inline-block;
    background-color: #000;
    width: 50px;
    height: 50px;
}

.filterDetail {
    font-family: "Consolas", "Menlo", "Ubuntu Mono", monospace;
}
</style>

<script>
'use strict';

class Color {
    constructor(r, g, b) {
        this.set(r, g, b);
    }
  
    toString() {
        return `rgb(${Math.round(this.r)}, ${Math.round(this.g)}, ${Math.round(this.b)})`;
    }

    set(r, g, b) {
        this.r = this.clamp(r);
        this.g = this.clamp(g);
        this.b = this.clamp(b);
    }

    hueRotate(angle = 0) {
        angle = angle / 180 * Math.PI;
        const sin = Math.sin(angle);
        const cos = Math.cos(angle);

        this.multiply([
            0.213 + cos * 0.787 - sin * 0.213,
            0.715 - cos * 0.715 - sin * 0.715,
            0.072 - cos * 0.072 + sin * 0.928,
            0.213 - cos * 0.213 + sin * 0.143,
            0.715 + cos * 0.285 + sin * 0.140,
            0.072 - cos * 0.072 - sin * 0.283,
            0.213 - cos * 0.213 - sin * 0.787,
            0.715 - cos * 0.715 + sin * 0.715,
            0.072 + cos * 0.928 + sin * 0.072,
        ]);
    }

    grayscale(value = 1) {
        this.multiply([
            0.2126 + 0.7874 * (1 - value),
            0.7152 - 0.7152 * (1 - value),
            0.0722 - 0.0722 * (1 - value),
            0.2126 - 0.2126 * (1 - value),
            0.7152 + 0.2848 * (1 - value),
            0.0722 - 0.0722 * (1 - value),
            0.2126 - 0.2126 * (1 - value),
            0.7152 - 0.7152 * (1 - value),
            0.0722 + 0.9278 * (1 - value),
        ]);
    }

    sepia(value = 1) {
        this.multiply([
            0.393 + 0.607 * (1 - value),
            0.769 - 0.769 * (1 - value),
            0.189 - 0.189 * (1 - value),
            0.349 - 0.349 * (1 - value),
            0.686 + 0.314 * (1 - value),
            0.168 - 0.168 * (1 - value),
            0.272 - 0.272 * (1 - value),
            0.534 - 0.534 * (1 - value),
            0.131 + 0.869 * (1 - value),
        ]);
    }

    saturate(value = 1) {
        this.multiply([
            0.213 + 0.787 * value,
            0.715 - 0.715 * value,
            0.072 - 0.072 * value,
            0.213 - 0.213 * value,
            0.715 + 0.285 * value,
            0.072 - 0.072 * value,
            0.213 - 0.213 * value,
            0.715 - 0.715 * value,
            0.072 + 0.928 * value,
        ]);
    }

    multiply(matrix) {
        const newR = this.clamp(this.r * matrix[0] + this.g * matrix[1] + this.b * matrix[2]);
        const newG = this.clamp(this.r * matrix[3] + this.g * matrix[4] + this.b * matrix[5]);
        const newB = this.clamp(this.r * matrix[6] + this.g * matrix[7] + this.b * matrix[8]);
        this.r = newR;
        this.g = newG;
        this.b = newB;
    }

    brightness(value = 1) {
        this.linear(value);
    }
    contrast(value = 1) {
        this.linear(value, -(0.5 * value) + 0.5);
    }

    linear(slope = 1, intercept = 0) {
        this.r = this.clamp(this.r * slope + intercept * 255);
        this.g = this.clamp(this.g * slope + intercept * 255);
        this.b = this.clamp(this.b * slope + intercept * 255);
    }

    invert(value = 1) {
        this.r = this.clamp((value + this.r / 255 * (1 - 2 * value)) * 255);
        this.g = this.clamp((value + this.g / 255 * (1 - 2 * value)) * 255);
        this.b = this.clamp((value + this.b / 255 * (1 - 2 * value)) * 255);
    }

    hsl() {
        // Code taken from https://stackoverflow.com/a/9493060/2688027, licensed under CC BY-SA.
        const r = this.r / 255;
        const g = this.g / 255;
        const b = this.b / 255;
        const max = Math.max(r, g, b);
        const min = Math.min(r, g, b);
        let h, s, l = (max + min) / 2;

        if (max === min) {
            h = s = 0;
        } else {
            const d = max - min;
            s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
            switch (max) {
                case r:
                    h = (g - b) / d + (g < b ? 6 : 0);
                    break;

                case g:
                    h = (b - r) / d + 2;
                    break;

                case b:
                    h = (r - g) / d + 4;
                    break;
            }
            h /= 6;
        }

        return {
            h: h * 100,
            s: s * 100,
            l: l * 100,
        };
    }

  clamp(value) {
      if (value > 255) {
          value = 255;
      } else if (value < 0) {
          value = 0;
      }
      return value;
  }
}

class Solver {
    constructor(target, baseColor) {
        this.target = target;
        this.targetHSL = target.hsl();
        this.reusedColor = new Color(0, 0, 0);
    }

    solve() {
        const result = this.solveNarrow(this.solveWide());
        return {
            values: result.values,
            loss: result.loss,
            filter: this.css(result.values),
        };
    }

    solveWide() {
        const A = 5;
        const c = 15;
        const a = [60, 180, 18000, 600, 1.2, 1.2];

        let best = { loss: Infinity };
        for (let i = 0; best.loss > 25 && i < 3; i++) {
            const initial = [50, 20, 3750, 50, 100, 100];
            const result = this.spsa(A, a, c, initial, 1000);
            if (result.loss < best.loss) {
                best = result;
            }
        }
        return best;
    }

    solveNarrow(wide) {
        const A = wide.loss;
        const c = 2;
        const A1 = A + 1;
        const a = [0.25 * A1, 0.25 * A1, A1, 0.25 * A1, 0.2 * A1, 0.2 * A1];
        return this.spsa(A, a, c, wide.values, 500);
    }

    spsa(A, a, c, values, iters) {
        const alpha = 1;
        const gamma = 0.16666666666666666;

        let best = null;
        let bestLoss = Infinity;
        const deltas = new Array(6);
        const highArgs = new Array(6);
        const lowArgs = new Array(6);

        for (let k = 0; k < iters; k++) {
            const ck = c / Math.pow(k + 1, gamma);
            for (let i = 0; i < 6; i++) {
                deltas[i] = Math.random() > 0.5 ? 1 : -1;
                highArgs[i] = values[i] + ck * deltas[i];
                lowArgs[i] = values[i] - ck * deltas[i];
            }

            const lossDiff = this.loss(highArgs) - this.loss(lowArgs);
            for (let i = 0; i < 6; i++) {
                const g = lossDiff / (2 * ck) * deltas[i];
                const ak = a[i] / Math.pow(A + k + 1, alpha);
                values[i] = fix(values[i] - ak * g, i);
            }

            const loss = this.loss(values);
            if (loss < bestLoss) {
                best = values.slice(0);
                bestLoss = loss;
            }
        }
        return { values: best, loss: bestLoss };

        function fix(value, idx) {
            let max = 100;
            if (idx === 2 /* saturate */) {
                max = 7500;
            } else if (idx === 4 /* brightness */ || idx === 5 /* contrast */) {
                max = 200;
            }

            if (idx === 3 /* hue-rotate */) {
                if (value > max) {
                    value %= max;
                } else if (value < 0) {
                    value = max + value % max;
                }
            } else if (value < 0) {
                value = 0;
            } else if (value > max) {
                value = max;
            }
            return value;
        }
    }

    loss(filters) {
        // Argument is array of percentages.
        const color = this.reusedColor;
        color.set(0, 0, 0);

        color.invert(filters[0] / 100);
        color.sepia(filters[1] / 100);
        color.saturate(filters[2] / 100);
        color.hueRotate(filters[3] * 3.6);
        color.brightness(filters[4] / 100);
        color.contrast(filters[5] / 100);

        const colorHSL = color.hsl();
        return (
        Math.abs(color.r - this.target.r) +
        Math.abs(color.g - this.target.g) +
        Math.abs(color.b - this.target.b) +
        Math.abs(colorHSL.h - this.targetHSL.h) +
        Math.abs(colorHSL.s - this.targetHSL.s) +
        Math.abs(colorHSL.l - this.targetHSL.l)
        );
    }

  css(filters) {
    function fmt(idx, multiplier = 1) {
        return Math.round(filters[idx] * multiplier);
    }
    return `filter: invert(${fmt(0)}%) sepia(${fmt(1)}%) saturate(${fmt(2)}%) hue-rotate(${fmt(3, 3.6)}deg) brightness(${fmt(4)}%) contrast(${fmt(5)}%);`;
  }
}

function hexToRgb(hex) {
    // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
    const shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
    hex = hex.replace(shorthandRegex, (m, r, g, b) => {
        return r + r + g + g + b + b;
    });

    const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result
        ? [
        parseInt(result[1], 16),
        parseInt(result[2], 16),
        parseInt(result[3], 16),
        ]
        : null;
}

document.addEventListener('DOMContentLoaded', function () {

    document.addEventListener('click', function (event) {

        // If the clicked element doesn't have the right selector, bail
        if (!event.target.matches('.execute')) return;

        // Don't follow the link
        event.preventDefault();

        const rgb = hexToRgb(document.getElementById('target-color').value);
        if (rgb.length !== 3) {
            alert('Invalid format!');
            return;
        }

        const color = new Color(rgb[0], rgb[1], rgb[2]);
        const solver = new Solver(color);
        var result = solver.solve();

        /*
        let lossMsg;
        var tries = 1;
        while ( result.loss > 0.1 ) {
            tries ++;
            result = solver.solve();
        }
        lossMsg = 'This is a perfect result in '+tries+' tries.';
        */

        let lossMsg;
        if (result.loss < 1) {
            lossMsg = 'This is a perfect result.';
        } else if (result.loss < 5) {
            lossMsg = 'This is close enough.';
        } else if (result.loss < 15) {
            lossMsg = 'The color is somewhat off. Consider running it again.';
        } else {
            lossMsg = 'The color is extremely off. Run it again!';
        }

        document.getElementById('lossDetail').innerHTML = `Loss: ${result.loss.toFixed(1)}. <b>${lossMsg}</b>`;
        document.getElementById('filterDetail').innerHTML = result.filter;
        document.getElementById('realPixel').style.backgroundColor = color.toString();
        document.getElementById('filterPixel').style = result.filter;

      }, false);

}, false);
</script>












        <?php
    }


    public function handle_form() {
        if( ! isset( $_POST['thr_flag_form'] ) || ! wp_verify_nonce( $_POST['thr_flag_form'], 'thr_flag_update' ) ){ ?>
            <div class="error">
                <p>Sorry, your nonce was not correct. Please try again.</p>
            </div><?php
            exit;
        } else {
            //require_once( 'inc/thelisresa-plugin-get-post-plus-update.php' );
            ?>
            <div class="updated">
                <p>Your fields were saved!</p>
            </div>
            <?php
        }
    }

}
new cssfiltergenerator_Plugin();
