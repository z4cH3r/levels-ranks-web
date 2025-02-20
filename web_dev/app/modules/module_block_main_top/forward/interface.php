<?php
    /**
     * @author Anastasia Sidak <m0st1ce.nastya@gmail.com>
     *
     * @link https://steamcommunity.com/profiles/76561198038416053
     * @link https://github.com/M0st1ce
     *
     * @license GNU General Public License Version 3
     */
?>
<div class="row">
    <?php if ( $Db->table_count['LevelsRanks'] % 2 == 1 ):?>
        <div class="col-md-3"><div class="card plug"></div></div>
    <?php endif;?>
    <?php for ( $d = 0; $d < $Db->table_count['LevelsRanks']; $d++ ):?>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                <h5 class="badge"><?php echo $Modules->get_translate_phrase( '_Top_players' )?>
                    :: <?php echo $Db->db_data['LevelsRanks'][ $d ]['name']?></h5>
                </div>
                <div class=table-responsive>
                    <table class="table table-hover mb-0">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <?php if( $General->arr_general['avatars'] != 0 ) {?><th class="text-right"></th><?php }?>
                            <th class="text-left"><?php echo $Modules->get_translate_phrase( '_Player' )?></th>
                            <th class="text-center"><?php echo $Modules->get_translate_phrase( '_Point' )?></th>
                            <th class="text-center"><?php echo $Modules->get_translate_phrase( '_Rank' )?></th>
                            <th class="text-center"><?php echo $Modules->get_translate_phrase( '_Ratio_KD_short' )?></th>
                            <th class="text-center tb-time-two"><?php echo $Modules->get_translate_phrase( '_Play_time' )?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $count = sizeof( $data['module_block_main_top'][ $d ] );
                        $data_paste[ $d ] = $data['module_block_main_top'][ $d ];
                        for ( $dd = 0; $dd < $count; $dd++ ) {
                            $General->get_js_relevance_avatar( $data_paste[ $d ][ $dd ]['steam'] )?>
                            <tr class="pointer" onclick="location.href = '<?php echo $General->arr_general['site'] ?>?page=profiles&profile=<?php echo $data_paste[ $d ][ $dd ]['steam'] ?>&server_group=<?php echo $d ?>';">
                                <th class="text-center"><?php echo $dd+1 ?></th>
                                <?php if( $General->arr_general['avatars'] != 0 ) {?>
                                    <th class="text-right"><img class="rounded-circle" id="<?php if ( $General->arr_general['avatars'] == 1){ echo con_steam32to64($data_paste[ $d ][ $dd ]['steam']);} ?>" data-src="
                                    <?php if ( $General->arr_general['avatars'] == 1){ echo $General->getAvatar( con_steam32to64($data_paste[ $d ][ $dd ]['steam']), 2);
                                    } elseif( $General->arr_general['avatars'] == 2) {
                                        echo 'storage/cache/img/avatars_random/' . rand(1,30) . '_xs.jpg';
                                    }?>"></th>
                                <?php }?>
                                <th class="text-left tb-name"><a
                                            href="<?php echo $General->arr_general['site'] ?>?page=profiles&profile=<?php echo $data_paste[ $d ][ $dd ]['steam'] ?>&server_group=<?php echo $d ?>"><?php echo action_text_clear( action_text_trim($data_paste[ $d ][ $dd ]['name'], 15) )?></a>
                                </th>
                                <th class="text-center"><?php echo number_format($data_paste[ $d ][ $dd ]['value'], 0, '.', ' ') ?></th>
                                <th class="text-center"><img
                                            data-src="<?php echo 'storage/cache/img/ranks/' . $Db->db_data['LevelsRanks'][ $d ]['ranks_pack'] . '/' . $data_paste[ $d ][ $dd ]['rank'] ?>.png">
                                </th>
                                <th class="text-center"><?php echo round($data_paste[ $d ][ $dd ]['kills'] / $data_paste[ $d ][ $dd ]['deaths'], 2); ?></th>
                                <th class="text-center tb-time-two"><?php echo round($data_paste[ $d ][ $dd ]['playtime'] / 60 / 60), ' ', $Modules->get_translate_phrase('_Hour') ?></th>
                            </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php if ( $Db->table_count['LevelsRanks'] % 2 == 1 && $d == 0 ):?>
            <div class="col-md-3"><div class="card plug"></div></div>
        <?php endif;?>
    <?php endfor;?>
</div>