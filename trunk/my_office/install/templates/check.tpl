<?php include 'header.tpl'?>
        <script type="text/javascript">
        $(function(){
            $('#post_form').submit(function(){
                var _compatible = '<?php echo $compatible?>';
                if (_compatible != '1')
                {
                    alert('<?php echo $lang[incompatible]?>');

                    return false;
                }
            });
        });
        </script>
        <?php if($messages){?>
        <ul class="messages">
        	<?php foreach($messages as $msg){?>           
            <li><?php echo $msg?></li>
           <?php }?>
        </ul>
        <?php }?>
        <div class="main_table">
            <h3><?php echo $lang[check_env]?></h3>
            <table>
                <tr>
                    <th width="35%"><?php echo $lang[item_name]?></th>
                    <th width="35%"><?php echo $lang[required_env]?></th>
                    <th><?php echo $lang[current_server]?></th>
                </tr>
            <?php foreach($check_env[detail] as $ek=>$ev){?>
                <tr>
                    <td><?php echo $lang[$ek]?></td>
                    <td><?php echo $ev[required]?></td>
                    <td class="ico <?php echo $ev[result]?>"><?php echo $ev[current]?></td>
                </tr>
            <?php }?>
            </table>

            <h3><?php echo $lang[file_and_folder_priv]?></h3>
            <table>
                <tr>
                    <th width="35%"><?php echo $lang[file_and_folder]?>}</th>
                    <th width="35%"><?php echo $lang[required_priv]?></th>
                    <th><?php echo $lang[current_priv]?></th>
                </tr>
              
                <?php foreach($check_file[detail] as $ek=>$ev){?>
                <tr>
                    <td><?php echo $ev[file]?></td>
                    <td class="ico"><?php echo $lang[writable]?></td>
                    <td class="ico <?php echo $ev[result]?>"><?php echo $ev[current]?></td>
                </tr>
               <?php }?>
            </table>
        </div>
        <div class="btn">
            <input type="hidden" value="<?php echo $compatible?>" name="compatible" />
            <input class="button mr10" type="button" value="<?php echo $lang[prev]?>" onclick="window.history.go(-1);" />
            <input type="submit" class="button <?php if (!$compatible){ echo 'mr10'; }?>" id="submit_button" value="<?php echo $lang[next]?>" />
            <?php if (!$compatible){?>
            <input type="button" class="button" value="<?php echo $lang[recheck]?>" onclick="window.location.reload();" />
            <?php }?>
        </div>
<?php include 'footer.tpl'?>