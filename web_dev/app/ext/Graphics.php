<?php
/**
 * @author Anastasia Sidak <m0st1ce.nastya@gmail.com>
 *
 * @link https://steamcommunity.com/profiles/76561198038416053
 * @link https://github.com/M0st1ce
 *
 * @license GNU General Public License Version 3
 */

namespace app\ext;

class Graphics {

    /**
     * Инициализация графической составляющей вэб-интерфейса с подгрузкой модулей.
     */
    function __construct( $General, $Modules, $Db ) {

        $Graphics = $this;

        $this->General = $General;

        // Подгрузка данных из модулей которые не относятся к интерфейсу и должны быть получены до начала рендера страницы
        for ( $module_id = 0, $c = sizeof( $Modules->arr_module_init['page'][ get_section( 'page', 'home' ) ]['data'] ); $module_id < $c; $module_id++ ):
            require MODULES . $Modules->arr_module_init['page'][ get_section( 'page', 'home' ) ]['data'][ $module_id ] . '/forward/data.php';
        endfor;

        // Рендер блока - Head
        require PAGE . 'head.php';

        // Рендер блока - Sidebar
        require PAGE . 'sidebar.php';

        // Рендер блока - Navbar
        require PAGE . 'navbar.php';

        // Подгрузка данных из модулей которые относятся к интерфейсу
        for ( $module_id = 0, $c = sizeof( $Modules->arr_module_init['page'][ get_section( 'page', 'home' ) ]['interface'] ); $module_id < $c; $module_id++ ):
            require MODULES . $Modules->arr_module_init['page'][ get_section( 'page', 'home' ) ]['interface'][ $module_id ] . '/forward/interface.php';
        endfor;

        // Рендер блока - Footer
        require PAGE . 'footer.php';
    }

    /**
     * Получение и вывод цветовой палитный сайта.
     *
     * @return string         Цветовая плалитра ( CSS / ROOT )
     */
    public function get_css_color_palette() {
        if ( isset ( $_SESSION['dark_mode'] ) && $_SESSION['dark_mode'] == true ) {
            return '<style> :root' . str_replace( ',', ';', str_replace( '"', '', file_get_contents_fix ( 'storage/assets/css/themes/' . $this->General->arr_general['theme'] . '/dark_mode_palette.json' ) ) ) .  '</style>';
        } else {
            return '<style> :root' . str_replace( ',', ';', str_replace( '"', '', file_get_contents_fix ( 'storage/assets/css/themes/' . $this->General->arr_general['theme'] . '/original_palette.json' ) ) ) .  '</style>';
        }
    }

    /**
     * Вывод информации свойтвах анимации на сайте.
     */
    public function get_css_animation() {
        if ( ! empty( $this->General->arr_general['animations'] ) && $this->General->arr_general['animations'] == true ) {
            return '
<style>
.global-container{transition: transform .5s ease-in-out, margin .5s ease-in-out} .offcanvas{transition: transform .5s ease-in-out, margin .5s ease-in-out}
[data-tooltip]:before, [data-tooltip]:after, .tooltip:before, .tooltip:after {
-webkit-transition: opacity 0.2s ease-in-out, visibility 0.2s ease-in-out, -webkit-transform 0.2s cubic-bezier(.71, 1.7, .77, 1.24);
-moz-transition: opacity 0.2s ease-in-out, visibility 0.2s ease-in-out, -moz-transform 0.2s cubic-bezier(.71, 1.7, .77, 1.24);
transition: opacity 0.2s ease-in-out, visibility 0.2s ease-in-out, transform 0.2s cubic-bezier(.71, 1.7, .77, 1.24);
}
</style>';
        }
    }
}