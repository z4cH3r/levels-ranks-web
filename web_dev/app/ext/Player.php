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

class Player {

    /**
     * @var string
     */
    public $steam_32;

    /**
     * @var int
     */
    public $steam_64;

    /**
     * @var int
     */
    public $server_group;

    /**
     * @var array
     */
    public $arr_default_info;

    /**
     * @var array
     */
    public $weapons;

    /**
     * @var array
     */
    public $unusualkills;

    /**
     * @var array
     */
    public $top_weapons;

    /**
     * @var int
     */
    public $top_position;

    /**
     * @var array
     */
    public $found;

    /**
     * @var //
     */
    public $Db;

    function __construct( $General, $Db, $id, $sg ) {

        # Работа с базой данных.
        $this->Db = $Db;

        # Работа с ядром.
        $this->General = $General;

        // Присвоение группы серверов.
        $this->server_group = (int) $sg;

        // Конвертация Steam ID
        substr( $id, 0, 5) === "STEAM" ? $this->steam_32 = $id : $this->steam_32 = con_steam64to32( $id );

        $check_it = false;

        // Поиск игрока в таблицах.
        for ( $i = 0; $i < $Db->table_count['LevelsRanks']; $i++ ):
            if ( $Db->query( 'LevelsRanks', $Db->db_data['LevelsRanks'][ $i ]['USER_ID'], $Db->db_data['LevelsRanks'][ $i ]['DB_num'], "SELECT steam FROM " . $Db->db_data['LevelsRanks'][ $i ]['Table'] . " WHERE steam LIKE '%" . $this->get_steam_32_short() . "%' limit 1" ) ):

                $this->found[$i] = [
                    "DB"            => $Db->db_data['LevelsRanks'][ $i ]['DB_num'],
                    "USER_ID"       => $Db->db_data['LevelsRanks'][ $i ]['USER_ID'],
                    "Table"         => $Db->db_data['LevelsRanks'][ $i ]['Table'],
                    'name_servers'  => $Db->db_data['LevelsRanks'][ $i ]['name'],
                    'mod'           => $Db->db_data['LevelsRanks'][ $i ]['mod'],
                    'steam'         => $Db->db_data['LevelsRanks'][ $i ]['steam'],
                    'ranks_pack'    => $Db->db_data['LevelsRanks'][ $i ]['ranks_pack'],
                    "server_group"  => $i
                ];

                if( $check_it == false ):
                    $check_it = $this->found[ $i ]['server_group'] == $this->server_group ? true : false;
                    if( ! empty( $_GET['search'] ) && $_GET['search'] == 1 ):
                        if ( ! empty( $this->found[ $i ]['server_group'] ) ):
                            $check_it = true;
                            if ( empty( $_GET['server_group'] ) ):
                                $this->server_group = $i;
                            endif;
                        endif;
                    endif;
                endif;
            endif;
        endfor;

        $this->found_fix = array_values( $this->found );

        $this->found[ $this->server_group ] == '' && header( 'Location: ' . $this->General->arr_general['site']) && exit;

        $this->arr_default_info = $this->get_db_arr_default_info();

        $this->top_position = $this->get_db_top_position();

        $this->top_with_player = $this->get_db_top_with_player();
        
        # Плагин -> Ex_weapons
        if ( $Db->mysql_table_search( 'LevelsRanks', $this->found[ $this->server_group ]['USER_ID'], $this->found[ $this->server_group ]['DB'], $this->found[ $this->server_group ]['Table'] . '_weapons' ) == 1 ):
            $this->weapons = $this->get_db_exstats_weapons();
        else:
            $this->weapons = ['weapon_knife' => '-','weapon_knife_m9_bayonet' => '-','weapon_knife_butterfly' => '-','weapon_knife_falchion' => '-','weapon_knife_def' => '-','weapon_knife_flip' => '-','weapon_knife_gut' => '-','weapon_knife_push' => '-','weapon_knife_t' => '-','weapon_knife_tactical' => '-'];
        endif;

        empty( $this->weapons ) && $this->weapons = ['weapon_knife' => '-','weapon_knife_m9_bayonet' => '-','weapon_knife_butterfly' => '-','weapon_knife_falchion' => '-','weapon_knife_def' => '-','weapon_knife_flip' => '-','weapon_knife_gut' => '-','weapon_knife_push' => '-','weapon_knife_t' => '-','weapon_knife_tactical' => '-'];

        arsort($this->weapons);

        for ( $i = 0; $i < 3; $i++ ):
            $this->top_weapons[ $i ]['name'] = array_search( max( $this->weapons ), $this->weapons );
            $this->top_weapons[ $i ]['kills'] = max( $this->weapons );
            unset( $this->weapons[ $this->top_weapons[ $i ]['name'] ] );
        endfor;

        # Плагин -> Unusual Kills
        if ( $Db->mysql_table_search( 'LevelsRanks', $this->found[ $this->server_group ]['USER_ID'], $this->found[ $this->server_group ]['DB'], $this->found[ $this->server_group ]['Table'] . '_unusualkills' ) == 1 ):
            $this->unusualkills = $this->get_db_plugin_module_unusualkills();
        else:
            $this->unusualkills = false;
        endif;

        # Плагин -> ExHits
        if ( $Db->mysql_table_search( 'LevelsRanks', $this->found[ $this->server_group ]['USER_ID'], $this->found[ $this->server_group ]['DB'], $this->found[ $this->server_group ]['Table'] . '_hits' ) == 1 ):
            $this->hits = $this->get_db_plugin_module_hits();
        else:
            $this->hits = ['Head' => 0, 'Chest' => 0, 'Belly' => 0, 'LeftArm' => 0,  'RightArm' => 0,  'LeftLeg' => 0,  'RightLeg' => 0,  'Neak' => 0];
        endif;
    }

    public function get_value() {
        return (int) empty( $this->arr_default_info['value'] ) ? 0 : $this->arr_default_info['value'];
    }

    public function get_steam_32() {
        $type = "/([0-9a-zA-Z_]{7}):([0-9]{1}):([0-9]+)/u";
        preg_match_all($type, $this->steam_32, $arr, PREG_SET_ORDER);
        return (string) $arr[0][1] . ':' . $arr[0][2] . ':' . $arr[0][3];
    }

    public function get_steam_32_short() {
        $type = "/[0-9a-zA-Z_]{7}:[0-9]{1}:([0-9]+)/u";
        preg_match_all($type, $this->steam_32, $arr, PREG_SET_ORDER);
        return (int) $arr[0][1];
    }

    public function get_steam_64() {
        return con_steam32to64( $this->get_steam_32() );
    }

    public function get_name() {
        return (string) empty( $this->arr_default_info['name'] ) ? 'Unknown' : $this->arr_default_info['name'];
    }

    public function get_rank() {
        return (int) empty( $this->arr_default_info['rank'] ) ? 0 : $this->arr_default_info['rank'];
    }

    public function get_kills() {
        return (int) empty( $this->arr_default_info['kills'] ) ? 0 : $this->arr_default_info['kills'];
    }

    public function get_deaths() {
        return (int) empty( $this->arr_default_info['deaths'] ) ? 0 : $this->arr_default_info['deaths'];
    }

    public function get_kd() {
        $a = empty( $this->get_deaths() ) ? 0 : round( $this->get_kills() / $this->get_deaths(), 2);
        return $a . ' ( ' . $this->get_kills() . ' / ' . $this->get_deaths() . ' )';
    }

    public function get_shoots() {
        return (int) $this->arr_default_info['shoots'];
    }

    public function get_hits() {
        return (int) $this->arr_default_info['hits'];
    }

    public function get_percent_hits() {
        $a = round( $this->get_shoots() / 100, 1 );
        $b = empty( $this->get_shoots() ) ? 0 : round( $this->get_hits() / $a, 1 );
        return $b . '% ( ' . $this->get_hits() . ' / ' . $this->get_shoots() . ' )';
    }

    public function get_headshots() {
        return (int) $this->arr_default_info['headshots'];
    }

    public function get_percent_headshots() {
        $a = round( $this->get_kills() / 100, 1 );
        $b = empty( $this->get_headshots() ) ? 0 : round( $this->get_headshots() / $a , 1 );
        return $this->arr_default_info['headshots'] . ' ( ' . $b . '% )';
    }

    public function get_assists() {
        return (int) $this->arr_default_info['assists'];
    }

    public function get_round_win() {
        return (int) $this->arr_default_info['round_win'];
    }

    public function get_round_lose() {
        return (int) $this->arr_default_info['round_lose'];
    }

    public function get_percent_win() {
        $a = round( $this->get_round_lose() / 100, 1 );
        $b = empty( $this->get_round_win() ) ? 0 : round( $this->get_round_win() / $a, 1 );
        return $b . '% (' . $this->get_round_win() . '/' . $this->get_round_lose() . ')';
    }

    public function get_playtime() {
        return (int) round( $this->arr_default_info['playtime'] / 60 / 60 , 0 );
    }

    public function get_top_position() {
        return (int) $this->top_position;
    }

    public function get_db_top_with_player() {
        $a = array_reverse($this->Db->queryAll( 'LevelsRanks', $this->found[ $this->server_group ]['USER_ID'], $this->found[ $this->server_group ]['DB'],"SELECT name, rank, steam, value FROM " . $this->found[ $this->server_group ]['Table'] . " WHERE '" . $this->get_value() . "' < value ORDER BY value ASC LIMIT 5" ) );
        $b = array_merge( $a, $this->Db->queryAll( 'LevelsRanks', $this->found[ $this->server_group ]['USER_ID'], $this->found[ $this->server_group ]['DB'],"SELECT name, rank, steam, value FROM " . $this->found[ $this->server_group ]['Table'] . " WHERE value <= '" . $this->get_value() . "' ORDER BY value DESC LIMIT 11" ) );
        return $b;
    }

    private function get_db_arr_default_info() {
        return $this->Db->query('LevelsRanks', $this->found[ $this->server_group ]['USER_ID'], $this->found[ $this->server_group ]['DB'], "SELECT name, rank, steam, playtime, value, kills, headshots, deaths,round_win,round_lose,shoots,hits FROM " . $this->found[ $this->server_group ]['Table'] . " WHERE steam LIKE '%" . $this->get_steam_32_short() . "%' LIMIT 1");
    }

    private function get_db_top_position() {
        return $this->Db->query( 'LevelsRanks', $this->found[ $this->server_group ]['USER_ID'], $this->found[ $this->server_group ]['DB'],"SELECT COUNT(1) AS `top` FROM (SELECT DISTINCT `value` FROM " . $this->found[ $this->server_group ]['Table'] . " WHERE `value` >= " . $this->get_value() . " AND `lastconnect` > 0) t;")['top'];
    }

    private function get_db_exstats_weapons() {
        if ( $this->found[ $this->server_group ]['mod'] == 'csgo' ) {
            return $this->Db->query('LevelsRanks', $this->found[ $this->server_group ]['USER_ID'], $this->found[ $this->server_group ]['DB'], "SELECT weapon_knife,weapon_taser,weapon_inferno,weapon_hegrenade,weapon_glock,weapon_hkp2000,weapon_tec9,weapon_usp,weapon_p250,weapon_cz75a,weapon_fiveseven,weapon_elite,weapon_revolver,weapon_deagle,weapon_negev,weapon_m249,weapon_mag7,weapon_sawedoff,weapon_nova,weapon_xm1014,weapon_bizon,weapon_mac10,weapon_ump45,weapon_mp9,weapon_mp7,weapon_p90,weapon_galilar,weapon_famas,weapon_ak47,weapon_m4a1,weapon_m4a1_silencer,weapon_aug,weapon_sg556,weapon_ssg08,weapon_awp,weapon_scar20,weapon_g3sg1,weapon_mp5sd FROM " . $this->found[ $this->server_group ]['Table'] . "_weapons WHERE steam LIKE '%" . $this->get_steam_32_short() . "%' LIMIT 1" );
        } elseif ( $this->found[ $this->server_group ]['mod'] == 'css' ){
            return $this->Db->query('LevelsRanks', $this->found[ $this->server_group ]['USER_ID'], $this->found[ $this->server_group ]['DB'], "SELECT weapon_usp, weapon_sg552, weapon_sg550, weapon_scout, weapon_galil, weapon_mp5navy, weapon_tmp, weapon_m3, weapon_p228, weapon_knife, weapon_glock, weapon_deagle, weapon_elite, weapon_fiveseven, weapon_xm1014, weapon_mac10, weapon_ump45, weapon_p90, weapon_famas, weapon_ak47, weapon_m4a1, weapon_awp, weapon_aug, weapon_ssg08, weapon_m249, weapon_g3sg1 FROM " . $this->found[ $this->server_group ]['Table'] . "_weapons WHERE steam LIKE '%" . $this->get_steam_32_short() . "%' LIMIT 1" );
        }
    }

    private function get_db_plugin_module_unusualkills() {
            return $this->Db->query('LevelsRanks', $this->found[ $this->server_group ]['USER_ID'], $this->found[ $this->server_group ]['DB'], "SELECT OP, Penetrated, NoScope, Run, Jump, Flash, Smoke, Whirl FROM " . $this->found[ $this->server_group ]['Table'] . "_unusualkills WHERE SteamID LIKE '%" . $this->get_steam_32_short() . "%' LIMIT 1" );
    }

    public function get_unusualkills_op() {
        return (int) $this->unusualkills['OP'];
    }

    public function get_unusualkills_penetrated() {
        return (int) $this->unusualkills['Penetrated'];
    }

    public function get_unusualkills_noscope() {
        return (int) $this->unusualkills['NoScope'];
    }

    public function get_unusualkills_run() {
        return (int) $this->unusualkills['Run'];
    }

    public function get_unusualkills_jump() {
        return (int) $this->unusualkills['Jump'];
    }

    public function get_unusualkills_flash() {
        return (int) $this->unusualkills['Flash'];
    }

    public function get_unusualkills_smoke() {
        return (int) $this->unusualkills['Smoke'];
    }

    public function get_unusualkills_whirl() {
        return (int) $this->unusualkills['Whirl'];
    }

    private function get_db_plugin_module_hits() {
        return $this->Db->query('LevelsRanks', $this->found[ $this->server_group ]['USER_ID'], $this->found[ $this->server_group ]['DB'], "SELECT Head, Chest, Belly, LeftArm, RightArm, LeftLeg, RightLeg, Neak FROM " . $this->found[ $this->server_group ]['Table'] . "_hits WHERE SteamID LIKE '%" . $this->get_steam_32_short() . "%' LIMIT 1" );
    }

    public function get_hits_all() {
        return (int) array_sum ( array_values ( $this->hits ) );
    }

    public function get_hits_head() {
        return (int) $this->hits['Head'] . '(' . action_int_percent_of_all( $this->hits['Head'], $this->get_hits_all() ) . '%)';
    }

    public function get_hits_chest() {
        return (int) $this->hits['Chest'] . '(' . action_int_percent_of_all( $this->hits['Chest'], $this->get_hits_all() ) . '%)';
    }

    public function get_hits_belly() {
        return (int) $this->hits['Belly'] . '(' . action_int_percent_of_all( $this->hits['Belly'], $this->get_hits_all() ) . '%)';
    }

    public function get_hits_leftarm() {
        return (int) $this->hits['LeftArm'] . '(' . action_int_percent_of_all( $this->hits['LeftArm'], $this->get_hits_all() ) . '%)';
    }

    public function get_hits_rightarm() {
        return (int) $this->hits['RightArm'] . '(' . action_int_percent_of_all( $this->hits['RightArm'], $this->get_hits_all() ) . '%)';
    }

    public function get_hits_leftleg() {
        return (int) $this->hits['LeftLeg'] . '(' . action_int_percent_of_all( $this->hits['LeftLeg'], $this->get_hits_all() ) . '%)';
    }

    public function get_hits_rightleg() {
        return (int) $this->hits['RightLeg'] . '(' . action_int_percent_of_all( $this->hits['RightLeg'], $this->get_hits_all() ) . '%)';
    }

    public function get_hits_neak() {
        return (int) $this->hits['Neak'] . '(' . action_int_percent_of_all( $this->hits['Neak'], $this->get_hits_all() ) . '%)';
    }
}