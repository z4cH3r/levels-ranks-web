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
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="badge"><?php echo $Modules->get_translate_phrase('_Comms')?></h5>
                <div class="select-panel select-panel-pages badge"><select onChange="window.location.href=this.value">
                        <option style="display:none" value="" disabled
                                selected><?php echo $page_num ?></option><?php for ($v = 0; $v < $page_max; $v++):?>
                        <option value="<?php echo set_url_section(get_url(2), 'num', $v + 1) ?>"><a
                                    href="<?php echo set_url_section(get_url(2), 'num', $v + 1) ?>"><?php echo $v + 1 ?></a>
                            </option><?php endfor;?></select></div>
            </div>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th class="text-center tb-game"><?php echo $Modules->get_translate_phrase('_Game') ?></th>
                    <th class="text-center"><?php echo $Modules->get_translate_phrase('_Date') ?></th>
                    <th class="text-center"><?php echo $Modules->get_translate_phrase('_Type') ?></th>
                    <?php if( $General->arr_general['avatars'] != 0 ) {?><th class="text-right tb-avatar"></th><?php }?>
                    <th class="text-left"><?php echo $Modules->get_translate_phrase('_Player') ?></th>
                    <?php if( $General->arr_general['avatars'] != 0 ) {?><th class="text-right tb-avatar"></th><?php }?>
                    <th class="text-left"><?php echo $Modules->get_translate_phrase('_Admin') ?></th>
                    <th class="text-left"><?php echo $Modules->get_translate_phrase('_Reason') ?></th>
                    <th class="text-center"><?php echo $Modules->get_translate_phrase('_Term') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php for ( $i = 0, $sz = sizeof( $res ); $i < $sz; $i++ ):
                    $General->get_js_relevance_avatar( $res[$i]['authid'] );
                    $res[$i]['aid'] != '0' && $General->get_js_relevance_avatar( $res[ $i ]['admin_authid'] )?><tr>
                        <th class="text-center tb-game"><img <?php $i  < '20' ? print 'src' : print 'data-src'?>="./storage/cache/img/mods/<?php echo $mod?>.png"></th>
                        <th class="text-center"><?php echo date('Y-m-d', $res[ $i ]['created']) ?></th>
                        <th class="text-center tb-type"><?php $res[ $i ]['type'] == 1 ? $General->get_icon( 'zmdi', 'mic', null ) : $General->get_icon( 'zmdi', 'comment-text', null )?></th>
                        <?php if( $General->arr_general['avatars'] != 0 ) {?>
                        <th class="text-right tb-avatar pointer" <?php if ($Modules->array_modules['module_page_profiles']['setting']['status'] == '1'){ ?>onclick="location.href = '<?php echo $General->arr_general['site'] ?>?page=profiles&profile=<?php echo $res[ $i ]['authid'] ?>&search=1' "<?php } ?>><img class="rounded-circle" id="<?php echo con_steam32to64($res[ $i ]['authid']) ?>"<?php $i  < '20' ? print 'src' : print 'data-src'?>="
                        <?php if ( $General->arr_general['avatars'] == 1){ echo $General->getAvatar(con_steam32to64($res[ $i ]['authid']), 2);
                            } elseif( $General->arr_general['avatars'] == 2) {
                                echo 'storage/cache/img/avatars_random/' . rand(1,30) . '_xs.jpg';
                            }?>"></th>
                        </th>
                        <?php } ?>
                        <th class="text-left pointer" <?php if ($Modules->array_modules['module_page_profiles']['setting']['status'] == '1'){ ?>onclick="location.href = '<?php echo $General->arr_general['site'] ?>?page=profiles&profile=<?php echo $res[ $i ]['authid'] ?>&search=1' "<?php } ?>>
                            <a <?php if ($Modules->array_modules['module_page_profiles']['setting']['status'] == '1'){ ?>href="<?php echo $General->arr_general['site'] ?>?page=profiles&profile=<?php echo $res[ $i ]['authid'] ?>&search=1"<?php } ?>><?php echo action_text_clear( action_text_trim($res[ $i ]['name'], 13) )?></a>
                        </th>
                        <?php if( $General->arr_general['avatars'] != 0 ) {?>
                        <th class="text-right tb-avatar <?php $res[ $i ]['aid'] != '0' && print 'a-type'?>" <?php if ($Modules->array_modules['module_page_profiles']['setting']['status'] == '1' && $res[ $i ]['aid'] != '0'){ ?>onclick="location.href = '<?php echo $General->arr_general['site'] ?>?page=profiles&profile=<?php echo $res[ $i ]['admin_authid'] ?>&search=1' "<?php } ?>><img class="rounded-circle" id="<?php echo con_steam32to64($res[ $i ]['admin_authid']) ?>"<?php $i  < '20' ? print 'src' : print 'data-src'?>="
                        <?php if( $res[ $i ]['admin_authid'] != 'STEAM_ID_SERVER' ) { if( $General->arr_general['avatars'] == 1){ echo $General->getAvatar(con_steam32to64($res[ $i ]['admin_authid']), 2);
                            } elseif( $General->arr_general['avatars'] == 2) { echo 'storage/cache/img/avatars_random/' . rand(1,30) . '_xs.jpg';
                            }?>
                        <?php } else {
                                echo 'storage/cache/img/avatars_random/20.jpg';
                            }?>"></th><?php }?>
                        <th class="text-left <?php $res[ $i ]['aid'] != '0' && print 'pointer'?>" <?php if ($Modules->array_modules['module_page_profiles']['setting']['status'] == '1' && $res[ $i ]['aid'] != '0'): ?>onclick="location.href = '<?php echo $General->arr_general['site'] ?>?page=profiles&profile=<?php echo $res[ $i ]['admin_authid'] ?>&search=1' "<?php endif; ?>>
                            <a <?php if ($Modules->array_modules['module_page_profiles']['setting']['status'] == '1' && $res[ $i ]['aid'] != '0'): ?>href="<?php echo $General->arr_general['site'] ?>?page=profiles&profile=<?php echo $res[ $i ]['admin_authid'] ?>&search=1"<?php endif; ?>><?php echo action_text_clear( action_text_trim($res[ $i ]['user'], 13) )?></a>
                        </th>
                        <th class="text-left"><?php echo $res[ $i ]['reason'] ?></th>
                        <th class="text-center"><?php
                            if ( $res[ $i ]['length'] == '0' && $res[ $i ]['RemoveType'] != 'U' ) {
                                echo $comms_type['0'];
                            } elseif ( $res[$i]['RemoveType'] == 'U') {
                                echo $comms_type['1'];
                            } elseif ( $res[$i]['length'] < '0' && time() >= $res[$i]['ends'] ) {
                                echo $comms_type['2'];
                            } elseif (time() >= $res[$i]['ends'] && $res[$i]['length'] != '0') {
                                echo '<div class="color-green"><strike>' . $General->action_time_exchange( $res[$i]['length'] ) . '</strike></div>';
                            }  else {
                                echo $General->action_time_exchange( $res[$i]['length'] );
                            }?>
                        </th>
                    </tr>
                <?php endfor; ?>
                </tbody>
            </table>
            <div class="card-bottom">
                <?php if( $page_max != 1):?>
                <div class="select-panel-pages">
                    <?php endif;?>
                    <?php if ($page_num != 1):?>
                        <a href="<?php echo set_url_section( get_url(2), 'num', $page_num - 1 ) ?>"><h5 class="badge"><?php $General->get_icon( 'zmdi', 'chevron-left' ) ?></h5></a>
                    <?php endif; ?>
                    <?php if( $page_num != $page_max ): ?>
                        <a href="<?php echo set_url_section( get_url(2), 'num', $page_num + 1 ) ?>"><h5 class="badge"><?php $General->get_icon( 'zmdi', 'chevron-right' ) ?></h5></a>
                    <?php endif; ?>
                    <?php if( $page_max != 1):?>
                </div>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>