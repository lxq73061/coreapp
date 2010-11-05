<?php include 'header.tpl'?>
        <script type="text/javascript">
        $(function(){
            <?php if ($site_url_error):?>
            $('#site_url').focus();
            <?php endif?>
            <?php if ($admin_email_error):?>
            $('#admin_email').focus();
            <?php endif?>
            <?php if ($pass_error):?>
            $('#pass_confirm').focus();
            <?php endif?>
        });
        </script>
        <?php if ($missing_items):?>
        <ul class="messages">
            <li><?php echo $lang[have_missing_item]?>:&nbsp;<?php foreach($missing_items as $item):?>[<?php echo $lang[$item]?>]&nbsp;&nbsp;<?php endforeach?></li>
        </ul>
        <?php endif?>
        <?php if ($mysql_error):?>
        <ul class="messages">
            <li><?php echo $lang[connect_mysql_failed]?>:<?php echo $mysql_error?></li>
        </ul>
        <?php endif?>
        <?php if ($create_db_error):?>
        <ul class="messages">
            <li><?php echo $lang[create_db_error]?>:<?php echo $create_db_error?></li>
        </ul>
        <?php endif?>
        <div class="main_form">
            <h3><?php echo $lang[db_info]?></h3>

            
            <table>
                <tr>
                    <th><?php echo $lang[db_host]?>:</th>
                    <td><input type="text" class="input_text" name="db_host" value="<?php echo $_POST[db_host] ? $_POST[db_host] :'localhost'?>" /></td>
                    <td class="color92a"><?php echo $lang[db_host_notice]?></td>
                </tr>
                <tr>
                    <th><?php echo $lang[db_port]?>:</th>
                    <td><input type="text" class="input_text" name="db_port" value="<?php echo $_POST[db_port] ? $_POST[db_port] :'3306'?>" /></td>
                    <td class="color92a"><?php echo $lang[db_port_notice]?></td>
                </tr>
                <tr>
                    <th><?php echo $lang[db_name]?>:</th>
                    <td><input type="text" class="input_text" name="db_name" value="<?php echo $_POST[db_name] ?>" /></td>
                    <td class="color92a"><?php echo $lang[db_name_notice]?></td>
                </tr>
                <tr>
                    <th><?php echo $lang[db_user]?>:</th>
                    <td><input type="text" class="input_text" name="db_user" value="<?php echo $_POST[db_user] ?>" /></td>
                    <td class="color92a"><?php echo $lang[db_user_notice]?></td>
                </tr>
                <tr>
                    <th><?php echo $lang[db_pass]?>:</th>
                    <td><input type="password" class="input_text" name="db_pass" value="<?php echo $_POST[db_pass] ?>" /></td>
                    <td class="color92a"><?php echo $lang[db_pass_notice]?></td>
                </tr>
                <tr>
                    <th><?php echo $lang[db_prefix]?>:</th>
                    <td><input type="text" class="input_text" name="db_prefix" value="<?php echo $_POST[db_prefix] ? $_POST[db_prefix] :'mdb_'?>" /></td>
                    <td class="color92a"><?php echo $lang[db_prefix_notice]?></td>
                </tr>
                <?php if ($has_myoffice):?>
                <tr>
                    <th>&nbsp;</th>
                    <td><input type="checkbox" name="force_install" id="force_install" value="1" />&nbsp;&nbsp;<label for="force_install"><?php echo $lang[force_install]?></label></td>
                    <td class="color92a"><strong style="color:red;"><?php echo $lang[force_install_notice]?></strong></td>
                </tr>
                <?php endif?>
            </table>

            <h3><?php echo $lang[init_info]?></h3>
            <table>
                <tr>
                    <th><?php echo $lang[site_url]?>:</th>
                    <td><input type="text" class="input_text" name="site_url" id="site_url" value="<?php if ($_POST['site_url']):?><?php echo $_POST[site_url] ?><?php else: ?><?echo $site_url?><?php endif?>" /></td>
                    <td class="color92a"><?php if ($site_url_error):?><strong style="color:red"><?php echo $lang[site_url_error]?></strong><?php else:?><?php echo $lang[site_url_notice]?><?php endif?></td>
                </tr>
                <tr>
                    <th><?php echo $lang[admin_name]?>:</th>
                    <td><input type="text" class="input_text" name="admin_name" value="<?php echo $_POST[admin_name] ?>" /></td>
                    <td class="color92a"><?php echo $lang[admin_name_notice]?></td>
                </tr>
                <tr>
                    <th><?php echo $lang[admin_email]?>:</th>
                    <td><input type="text" class="input_text" id="admin_email" name="admin_email" value="<?php echo $_POST[admin_email] ?>" /></td>
                    <td class="color92a"><?php if ($admin_email_error):?><strong style="color:red"><?php echo $lang[admin_email_error]?></strong><?php else:?><?php echo $lang[admin_email_notice]?><?php endif?></td>
                </tr>
                <tr>
                    <th><?php echo $lang[admin_pass]?>:</th>
                    <td><input type="password" class="input_text" name="admin_pass" value="<?php echo $_POST[admin_pass] ?>" /></td>
                    <td class="color92a"><?php echo $lang[admin_pass_notice]?></td>
                </tr>
                <tr>
                    <th><?php echo $lang[pass_confirm]?>:</th>
                    <td><input type="password" class="input_text" id="pass_confirm" name="pass_confirm" value="<?php echo $_POST[pass_confirm] ?>" /></td>
                    <td class="color92a"><?php if ($pass_error):?><strong style="color:red"><?php echo $lang[pass_error]?></strong><?php else:?><?php echo $lang[pass_confirm_notice]?><?php endif?></td>
                </tr>
                <tr>
                    <th></th>
                    <td>
                        <input type="button" class="button mtb20" style="margin-right:10px;" value="<?php echo $lang[prev]?>" onclick="window.history.go(-1);" />
                        <input type="submit" class="button mtb20" id="submit_button" value="<?php echo $lang[next]?>" />
                    </td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </div>
<?php include 'footer.tpl'?>